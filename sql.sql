-- 1. ENUM types
CREATE TYPE role_enum AS ENUM ('employe', 'technicien', 'administrateur');
CREATE TYPE statut_equipement_enum AS ENUM ('en_service', 'hors_service', 'en_reparation');
CREATE TYPE statut_demande_enum AS ENUM (
  'en_attente',  /* créée par l'employé */
  'attribuee',   /* assignée à un ou plusieurs techniciens */
  'en_cours',    /* un technicien a démarré l'intervention */
  'resolue',     /* clôturée automatiquement */
  'refusee'      /* refusée par l'admin */
);

-- 2. Utilisateur
CREATE TABLE utilisateur (
  id_utilisateur SERIAL PRIMARY KEY,
  nom        VARCHAR(100) NOT NULL,
  prenom     VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,
  actif      BOOLEAN NOT NULL DEFAULT TRUE,  -- soft‐delete flag
  date_creation    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP
);

-- 3. Roles many‐to‐many
CREATE TABLE utilisateur_role (
  id_utilisateur INT NOT NULL
    REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE,
  role           role_enum NOT NULL,
  PRIMARY KEY (id_utilisateur, role)
);

-- 4. Equipement, with owner
CREATE TABLE equipement (
  id_equipement SERIAL PRIMARY KEY,
  reference     VARCHAR(50) NOT NULL UNIQUE,
  type          VARCHAR(100) NOT NULL,
  statut        statut_equipement_enum NOT NULL DEFAULT 'en_service',
  date_achat    DATE,
  localisation  VARCHAR(150),
  id_proprietaire INT NOT NULL
    REFERENCES utilisateur(id_utilisateur)
    ON DELETE RESTRICT,
  date_creation    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP
);

-- 5. Demande de réparation
CREATE TABLE demande_reparation (
  id_demande SERIAL PRIMARY KEY,
  id_utilisateur INT NOT NULL  -- qui a fait la demande
    REFERENCES utilisateur(id_utilisateur) ON DELETE RESTRICT,
  id_equipement  INT NOT NULL
    REFERENCES equipement(id_equipement) ON DELETE RESTRICT,
  description    TEXT NOT NULL,
  statut         statut_demande_enum NOT NULL DEFAULT 'en_attente',
  date_creation    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_mise_a_jour TIMESTAMP
);

-- 6. Liaison demande ↔ technicien
CREATE TABLE demande_technicien (
  id_demande      INT NOT NULL
    REFERENCES demande_reparation(id_demande) ON DELETE CASCADE,
  id_utilisateur  INT NOT NULL  -- le technicien
    REFERENCES utilisateur(id_utilisateur) ON DELETE RESTRICT,
  date_attribution TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_debut       TIMESTAMP,
  date_fin         TIMESTAMP,
  action_technicien TEXT,
  PRIMARY KEY (id_demande, id_utilisateur)
);

-- 7. Trigger: quand un tech commence la répa, passe l'équipement en 'en_reparation'
CREATE OR REPLACE FUNCTION trg_set_equipement_en_reparation() RETURNS TRIGGER AS $$
BEGIN
  UPDATE equipement
    SET statut = 'en_reparation', date_modification = CURRENT_TIMESTAMP
  WHERE id_equipement = (SELECT id_equipement FROM demande_reparation WHERE id_demande = NEW.id_demande);
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_after_assign
AFTER INSERT ON demande_technicien
FOR EACH ROW
EXECUTE FUNCTION trg_set_equipement_en_reparation();

-- 8. Trigger: quand tous les techs ont enregistré, clôture demande + remet équipement en service
CREATE OR REPLACE FUNCTION update_demande_and_equipement() RETURNS TRIGGER AS $$
DECLARE
  unfinished_count INT;
  equip_id INT;
BEGIN
  SELECT COUNT(*) INTO unfinished_count
    FROM demande_technicien dt
    WHERE dt.id_demande = NEW.id_demande
      AND dt.action_technicien IS NULL;

  IF unfinished_count = 0 THEN
    -- 1) Clôture la demande
    UPDATE demande_reparation
      SET statut = 'resolue',
          date_mise_a_jour = CURRENT_TIMESTAMP
    WHERE id_demande = NEW.id_demande;

    -- 2) Remet l'équipement en service
    SELECT id_equipement INTO equip_id
      FROM demande_reparation
     WHERE id_demande = NEW.id_demande;

    UPDATE equipement
      SET statut = 'en_service', date_modification = CURRENT_TIMESTAMP
    WHERE id_equipement = equip_id;
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_after_action
AFTER INSERT OR UPDATE OF action_technicien ON demande_technicien
FOR EACH ROW
WHEN (NEW.action_technicien IS NOT NULL)
EXECUTE FUNCTION update_demande_and_equipement();










-- 1) Users
INSERT INTO utilisateur (nom, prenom, email, mot_de_passe)
VALUES
  ('Dupont',   'Alice',   'alice.dupont@example.com',   'password1'),
  ('Martin',   'Bob',     'bob.martin@example.com',     'password2'),
  ('Khan',     'Charlie', 'charlie.khan@example.com',   'password3'),
  ('Nguyen',   'Diane',   'diane.nguyen@example.com',   'password4'),
  ('Singh',    'Eve',     'eve.singh@example.com',       'password5'),
  ('Garcia',   'Franck',  'franck.garcia@example.com',   'password6'),
  ('Lopez',    'Gina',    'gina.lopez@example.com',      'password7');

-- 2) Assign Roles
-- Alice & Bob are just employees
-- Charlie & Diane are employees + technicians
-- Eve is employee + administrator
-- Franck & Gina are full admins
INSERT INTO utilisateur_role (id_utilisateur, role) VALUES
  (1, 'employe'),
  (2, 'employe'),
  (3, 'employe'), (3, 'technicien'),
  (4, 'employe'), (4, 'technicien'),
  (5, 'employe'), (5, 'administrateur'),
  (6, 'employe'), (6, 'administrateur'),
  (7, 'employe'), (7, 'administrateur');

-- 3) Equipment
INSERT INTO equipement (reference, type, date_achat, localisation, id_proprietaire)
VALUES
  ('PC-001', 'Ordinateur Portable', '2023-02-15', 'Bureau 101', 1),
  ('PC-002', 'Ordinateur de Bureau',  '2022-11-01', 'Bureau 102', 1),
  ('PRJ-010','Projecteur',           '2021-09-12', 'Salle R1',    2),
  ('IMPR-23','Imprimante Laser',     '2024-01-05', 'Bureau 103',  3),
  ('NAS-07', 'Serveur NAS',          '2020-06-20', 'Data Center', 4),
  ('SW-12',  'Switch Réseau',        '2019-12-01', 'Data Center', 5);

-- 4) Repair Requests (employees 1,2,3,4 create)
INSERT INTO demande_reparation (id_utilisateur, id_equipement, description)
VALUES
  (1, 1, 'Écran ne s’allume plus'),
  (1, 2, 'Clavier défectueux'),
  (2, 3, 'Image floue sur le projecteur'),
  (3, 4, 'Erreur papier sur imprimante'),
  (4, 5, 'Accès au NAS refusé'),
  (2, 1, 'Ventilateur bruyant');

-- 5) Assign some requests to technicians
-- Assign request 1 & 2 to Charlie(3), Diane(4)
INSERT INTO demande_technicien (id_demande, id_utilisateur)
VALUES
  (1, 3),
  (1, 4),
  (2, 3),
  (3, 4);

-- At this point triggers set equip.1 & equip.2 & equip.3 to 'en_reparation'

-- 6) Technicians record actions
-- Charlie finishes req #1
INSERT INTO demande_technicien (id_demande, id_utilisateur, date_attribution, date_debut, date_fin, action_technicien)
VALUES
  (1, 3, NOW() - INTERVAL '2 days', NOW() - INTERVAL '2 days', NOW() - INTERVAL '1 day', 'Changement de l’écran défectueux.');

-- Diane finishes req #1 (this will auto-close request 1 and set equip.1 back to 'en_service')
UPDATE demande_technicien
   SET date_debut = NOW() - INTERVAL '2 days',
       date_fin     = NOW() - INTERVAL '3 hours',
       action_technicien = 'Vérification OK et rapport envoyé.'
 WHERE id_demande = 1 AND id_utilisateur = 4;

-- Charlie starts but doesn’t finish req #2
UPDATE demande_technicien
   SET date_debut = NOW() - INTERVAL '1 day'
 WHERE id_demande = 2 AND id_utilisateur = 3;

-- 7) Admin (Eve — user 5) refuses request 6
INSERT INTO demande_technicien (id_demande, id_utilisateur)
VALUES
  (6, 5);  -- we use admin in the technicien table to model a “refuse” action

UPDATE demande_reparation
   SET statut = 'refusee',
       date_mise_a_jour = NOW()
 WHERE id_demande = 6;

-- 8) Bonus: assign request 5 to Gina(7) and Franck(6)
INSERT INTO demande_technicien (id_demande, id_utilisateur)
VALUES
  (5, 7),
  (5, 6);

-- All done
COMMIT;
