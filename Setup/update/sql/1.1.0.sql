ALTER TABLE `team` DROP COLUMN `version`, DROP COLUMN `version_created_at`, DROP COLUMN `version_created_by`;
ALTER TABLE `person_team_link` DROP COLUMN `version`, DROP COLUMN `version_created_at`, DROP COLUMN `version_created_by`;
ALTER TABLE `person` DROP COLUMN `version`, DROP COLUMN `version_created_at`, DROP COLUMN `version_created_by`;
ALTER TABLE `person_image` DROP COLUMN `version`, DROP COLUMN `version_created_at`, DROP COLUMN `version_created_by`;
ALTER TABLE `person_function_link` DROP COLUMN `version`, DROP COLUMN `version_created_at`, DROP COLUMN `version_created_by`;
ALTER TABLE `person_function` DROP COLUMN `version`, DROP COLUMN `version_created_at`, DROP COLUMN `version_created_by`;

DROP TABLE `team_version`;
DROP TABLE `person_team_link_version`;
DROP TABLE `person_version`;
DROP TABLE `person_image_version`;
DROP TABLE `person_function_link_version`;
DROP TABLE `person_function_version`;