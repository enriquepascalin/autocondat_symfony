<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617043044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE acknowledgement (id SERIAL NOT NULL, tenant_id INT NOT NULL, autocondat_user_id INT NOT NULL, notification_id INT NOT NULL, acknowledged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, action INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_118AB7B59033212A ON acknowledgement (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_118AB7B53F4605C0 ON acknowledgement (autocondat_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_118AB7B5EF1A9D84 ON acknowledgement (notification_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN acknowledgement.acknowledged_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actor (id SERIAL NOT NULL, type INT NOT NULL, reference_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE audience (id SERIAL NOT NULL, tenant_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FDCD94189033212A ON audience (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE audience_segment (audience_id INT NOT NULL, segment_id INT NOT NULL, PRIMARY KEY(audience_id, segment_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C5C2F52F848CC616 ON audience_segment (audience_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C5C2F52FDB296AAD ON audience_segment (segment_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE audience_user (audience_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(audience_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8A619601848CC616 ON audience_user (audience_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8A619601A76ED395 ON audience_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE bundle (id SERIAL NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type INT NOT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A57B32FD9033212A ON bundle (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A57B32FDDE12AB56 ON bundle (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A57B32FD16FE72E1 ON bundle (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN bundle.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN bundle.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN bundle.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE bundle_component (id SERIAL NOT NULL, bundle_id INT NOT NULL, feature_id INT DEFAULT NULL, service_id INT DEFAULT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, quantity INT DEFAULT NULL, access_tier VARCHAR(50) DEFAULT NULL, overrides JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5BE9E38F1FAD9D3 ON bundle_component (bundle_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5BE9E3860E4B879 ON bundle_component (feature_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5BE9E38ED5CA9E6 ON bundle_component (service_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5BE9E389033212A ON bundle_component (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5BE9E38DE12AB56 ON bundle_component (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5BE9E3816FE72E1 ON bundle_component (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN bundle_component.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN bundle_component.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN bundle_component.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE business_rule (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, type INT NOT NULL, parameters JSON DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE calendar (id SERIAL NOT NULL, tenant_id INT NOT NULL, name VARCHAR(255) NOT NULL, timezone VARCHAR(255) DEFAULT NULL, is_default BOOLEAN NOT NULL, is_external_sync BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6EA9A1469033212A ON calendar (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE calendar_event (id SERIAL NOT NULL, calendar_id INT NOT NULL, recurrence_rule_id INT DEFAULT NULL, event_type_id INT NOT NULL, task_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, external_event_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_57FA09C9A40A2C8 ON calendar_event (calendar_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_57FA09C92344888A ON calendar_event (recurrence_rule_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_57FA09C9401B253C ON calendar_event (event_type_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_57FA09C98DB60186 ON calendar_event (task_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE channel (id SERIAL NOT NULL, tenant_id INT NOT NULL, type INT NOT NULL, provider INT NOT NULL, config JSON DEFAULT NULL, is_default BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A2F98E479033212A ON channel (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE consent_log (id SERIAL NOT NULL, autocondat_user_id INT NOT NULL, consent_type VARCHAR(50) NOT NULL, consent_given BOOLEAN NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, version VARCHAR(20) NOT NULL, ip_address VARCHAR(45) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_301137293F4605C0 ON consent_log (autocondat_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN consent_log.timestamp IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE deadline (id SERIAL NOT NULL, task_id INT NOT NULL, type INT NOT NULL, value VARCHAR(50) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B74774F28DB60186 ON deadline (task_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE delivery_rule (id SERIAL NOT NULL, tenant_id INT NOT NULL, notification_id INT NOT NULL, name VARCHAR(255) NOT NULL, retry_policy JSON DEFAULT NULL, require_acknowledgment BOOLEAN NOT NULL, max_ack_attempts INT NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_974464569033212A ON delivery_rule (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_97446456EF1A9D84 ON delivery_rule (notification_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN delivery_rule.expires_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE delivery_rule_segment (delivery_rule_id INT NOT NULL, segment_id INT NOT NULL, PRIMARY KEY(delivery_rule_id, segment_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_779635D57E50BC8F ON delivery_rule_segment (delivery_rule_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_779635D5DB296AAD ON delivery_rule_segment (segment_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE delivery_rule_channel (delivery_rule_id INT NOT NULL, channel_id INT NOT NULL, PRIMARY KEY(delivery_rule_id, channel_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CDEE4EF77E50BC8F ON delivery_rule_channel (delivery_rule_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CDEE4EF772F5A1AA ON delivery_rule_channel (channel_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE document (id SERIAL NOT NULL, project_id INT DEFAULT NULL, tenant_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D8698A76166D1F9C ON document (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D8698A769033212A ON document (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_type (id SERIAL NOT NULL, tenant_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(7) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_93151B829033212A ON event_type (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE feature (id SERIAL NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, technical_metadata JSON NOT NULL, commercial_metadata JSON NOT NULL, type INT NOT NULL, is_core BOOLEAN NOT NULL, is_marketplace_item BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1FD775669033212A ON feature (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1FD77566DE12AB56 ON feature (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1FD7756616FE72E1 ON feature (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN feature.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN feature.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN feature.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE feature_feature (feature_source INT NOT NULL, feature_target INT NOT NULL, PRIMARY KEY(feature_source, feature_target))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2EC0EE6F269F99DD ON feature_feature (feature_source)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2EC0EE6F3F7AC952 ON feature_feature (feature_target)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE license (id SERIAL NOT NULL, subscription_id INT NOT NULL, feature_id INT NOT NULL, assigned_user_id INT DEFAULT NULL, usage_limit INT DEFAULT NULL, valid_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, valid_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5768F4199A1887DC ON license (subscription_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5768F41960E4B879 ON license (feature_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5768F419ADF66B1A ON license (assigned_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN license.valid_from IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE marketplace_item (id SERIAL NOT NULL, monetization_policy_id INT NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, item_id UUID NOT NULL, type INT NOT NULL, approval_status INT NOT NULL, compatibility_matrix JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D600F78F589157E ON marketplace_item (monetization_policy_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D600F789033212A ON marketplace_item (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D600F78DE12AB56 ON marketplace_item (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D600F7816FE72E1 ON marketplace_item (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN marketplace_item.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN marketplace_item.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN marketplace_item.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE marketplace_profile (id SERIAL NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, display_name VARCHAR(255) NOT NULL, status INT NOT NULL, payment_details JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65B179139033212A ON marketplace_profile (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65B17913DE12AB56 ON marketplace_profile (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65B1791316FE72E1 ON marketplace_profile (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN marketplace_profile.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN marketplace_profile.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN marketplace_profile.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE marketplace_transaction (id SERIAL NOT NULL, subscription_id INT NOT NULL, subscriptor_id INT NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, currency VARCHAR(3) NOT NULL, platform_fee NUMERIC(10, 2) DEFAULT NULL, type INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_487414949A1887DC ON marketplace_transaction (subscription_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_487414945769024E ON marketplace_transaction (subscriptor_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_487414949033212A ON marketplace_transaction (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_48741494DE12AB56 ON marketplace_transaction (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4874149416FE72E1 ON marketplace_transaction (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN marketplace_transaction.date IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN marketplace_transaction.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN marketplace_transaction.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN marketplace_transaction.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE monetization_policy (id SERIAL NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, pricing_model INT NOT NULL, price_configuration JSON NOT NULL, currency VARCHAR(3) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CAA56AE29033212A ON monetization_policy (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CAA56AE2DE12AB56 ON monetization_policy (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CAA56AE216FE72E1 ON monetization_policy (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN monetization_policy.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN monetization_policy.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN monetization_policy.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notification (id SERIAL NOT NULL, task_id INT DEFAULT NULL, tenant_id INT NOT NULL, audience_id INT DEFAULT NULL, linked_document_id INT DEFAULT NULL, type INT NOT NULL, content TEXT DEFAULT NULL, sent_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status INT NOT NULL, is_mandatory_ack BOOLEAN NOT NULL, is_blocking_alert BOOLEAN NOT NULL, scheduled_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CA8DB60186 ON notification (task_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CA9033212A ON notification (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CA848CC616 ON notification (audience_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CA2B1068DF ON notification (linked_document_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN notification.sent_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notification_log (id SERIAL NOT NULL, tenant_id INT NOT NULL, notification_id INT NOT NULL, channel_id INT NOT NULL, status INT NOT NULL, error TEXT DEFAULT NULL, is_acknowledged BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_ED15DF29033212A ON notification_log (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_ED15DF2EF1A9D84 ON notification_log (notification_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_ED15DF272F5A1AA ON notification_log (channel_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE permission (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, scope INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project (id SERIAL NOT NULL, tenant_id INT NOT NULL, name VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, type INT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2FB3D0EE9033212A ON project (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_document (id SERIAL NOT NULL, project_id INT NOT NULL, document_id INT DEFAULT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, category INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E52701AD166D1F9C ON project_document (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_E52701ADC33F7837 ON project_document (document_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E52701AD9033212A ON project_document (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E52701ADDE12AB56 ON project_document (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E52701AD16FE72E1 ON project_document (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_document.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_document.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN project_document.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_phase (id SERIAL NOT NULL, tenant_id INT NOT NULL, project_id INT NOT NULL, parent_phase_id INT DEFAULT NULL, workflow_execution_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type INT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A2E8DF559033212A ON project_phase (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A2E8DF55166D1F9C ON project_phase (project_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A2E8DF556C4BD59 ON project_phase (parent_phase_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_A2E8DF559448F50E ON project_phase (workflow_execution_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_phase_project_phase (project_phase_source INT NOT NULL, project_phase_target INT NOT NULL, PRIMARY KEY(project_phase_source, project_phase_target))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C7088B47CBFAAD8A ON project_phase_project_phase (project_phase_source)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C7088B47D21FFD05 ON project_phase_project_phase (project_phase_target)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_phase_user (project_phase_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(project_phase_id, user_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65B21055A4479A53 ON project_phase_user (project_phase_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65B21055A76ED395 ON project_phase_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_phase_assignment (id SERIAL NOT NULL, tenant_id INT NOT NULL, phase_id INT NOT NULL, autocondat_user_id INT NOT NULL, role INT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9DFBD2629033212A ON project_phase_assignment (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9DFBD26299091188 ON project_phase_assignment (phase_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9DFBD2623F4605C0 ON project_phase_assignment (autocondat_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE recurrance_rule (id SERIAL NOT NULL, frequency INT NOT NULL, interval INT NOT NULL, until TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, excluded_dates JSON DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reset_password_request (id SERIAL NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN reset_password_request.requested_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN reset_password_request.expires_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role (id SERIAL NOT NULL, tenant_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_57698A6A9033212A ON role (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE segment (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE service (id SERIAL NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(100) NOT NULL, code VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, service_levels JSON DEFAULT NULL, type INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E19D9AD29033212A ON service (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E19D9AD2DE12AB56 ON service (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E19D9AD216FE72E1 ON service (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN service.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN service.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN service.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE session (id SERIAL NOT NULL, autocondat_user_id INT NOT NULL, token VARCHAR(255) NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_used TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ip_adress VARCHAR(45) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, is_revoked BOOLEAN NOT NULL, user_agent_hash VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D044D5D43F4605C0 ON session (autocondat_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE settlement_ledger (id SERIAL NOT NULL, transaction_id INT NOT NULL, amount NUMERIC(12, 2) NOT NULL, currency VARCHAR(3) NOT NULL, status INT NOT NULL, settled_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_529382E12FC0CB0F ON settlement_ledger (transaction_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN settlement_ledger.settled_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE state (id SERIAL NOT NULL, workflow_id INT NOT NULL, name VARCHAR(100) NOT NULL, type INT NOT NULL, execution_order INT NOT NULL, metadata JSON DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A393D2FB2C7C2CBA ON state (workflow_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE subscription (id SERIAL NOT NULL, bundle_id INT NOT NULL, parent_subscription_id INT DEFAULT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status INT NOT NULL, auto_renew BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A3C664D3F1FAD9D3 ON subscription (bundle_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A3C664D3DD48320 ON subscription (parent_subscription_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A3C664D39033212A ON subscription (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A3C664D3DE12AB56 ON subscription (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A3C664D316FE72E1 ON subscription (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN subscription.start_date IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN subscription.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN subscription.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN subscription.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE task (id SERIAL NOT NULL, workflow_execution_id INT NOT NULL, assignee_id INT NOT NULL, project_task_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, status INT NOT NULL, priority INT NOT NULL, due_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_527EDB259448F50E ON task (workflow_execution_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_527EDB2559EC7D60 ON task (assignee_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_527EDB251BA80DE3 ON task (project_task_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tenant (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_financial BOOLEAN NOT NULL, is_operational BOOLEAN NOT NULL, code VARCHAR(50) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4E59C462727ACA70 ON tenant (parent_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tenant_segment (tenant_id INT NOT NULL, segment_id INT NOT NULL, PRIMARY KEY(tenant_id, segment_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A17B7519033212A ON tenant_segment (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A17B751DB296AAD ON tenant_segment (segment_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tenant_audit_log (id SERIAL NOT NULL, tenant_id INT NOT NULL, changed_by_id INT NOT NULL, changed_field VARCHAR(100) NOT NULL, old_value JSON DEFAULT NULL, new_value JSON DEFAULT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D51901289033212A ON tenant_audit_log (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D5190128828AD0A0 ON tenant_audit_log (changed_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN tenant_audit_log.timestamp IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tenant_config (id SERIAL NOT NULL, tenant_id INT NOT NULL, key VARCHAR(100) NOT NULL, value JSON NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F5214B259033212A ON tenant_config (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tenant_users (id SERIAL NOT NULL, tenant_id INT NOT NULL, autocondat_user_id INT NOT NULL, role_id INT NOT NULL, valid_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, valid_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B1349DD9033212A ON tenant_users (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B1349DD3F4605C0 ON tenant_users (autocondat_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B1349DDD60322AC ON tenant_users (role_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ticket (id SERIAL NOT NULL, owner_id INT NOT NULL, agent_id INT DEFAULT NULL, reported_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, solved_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type INT NOT NULL, severity INT NOT NULL, status INT NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_97A0ADA37E3C61F9 ON ticket (owner_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_97A0ADA33414710B ON ticket (agent_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN ticket.reported_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN ticket.solved_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE transition (id SERIAL NOT NULL, sourcec_state_id INT NOT NULL, target_state_id INT NOT NULL, workflow_id INT NOT NULL, name VARCHAR(100) NOT NULL, condition_mode INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F715A75A314988D9 ON transition (sourcec_state_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F715A75ADF73ECA8 ON transition (target_state_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F715A75A2C7C2CBA ON transition (workflow_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE translation_entry (id SERIAL NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, locale VARCHAR(255) NOT NULL, domain VARCHAR(255) NOT NULL, key VARCHAR(255) NOT NULL, value TEXT NOT NULL, source INT DEFAULT NULL, is_override BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4CD1F51F9033212A ON translation_entry (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4CD1F51FDE12AB56 ON translation_entry (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4CD1F51F16FE72E1 ON translation_entry (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN translation_entry.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN translation_entry.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN translation_entry.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE trigger (id SERIAL NOT NULL, transition_id INT NOT NULL, type INT NOT NULL, value TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1A6B0F5D8BF1A064 ON trigger (transition_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, role INT NOT NULL, mfa_secret VARCHAR(255) DEFAULT NULL, is_mfa_enabled BOOLEAN NOT NULL, password_reset_token VARCHAR(255) DEFAULT NULL, password_reset_expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, mfa_backup_codes JSON DEFAULT NULL, locale VARCHAR(10) DEFAULT NULL, is_verified BOOLEAN NOT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D6499033212A ON "user" (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649DE12AB56 ON "user" (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D64916FE72E1 ON "user" (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".password_reset_expires_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_segment (user_id INT NOT NULL, segment_id INT NOT NULL, PRIMARY KEY(user_id, segment_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_408CEB6FA76ED395 ON user_segment (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_408CEB6FDB296AAD ON user_segment (segment_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE workflow (id SERIAL NOT NULL, feature_id INT NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, version VARCHAR(20) DEFAULT NULL, is_enabled BOOLEAN NOT NULL, priority INT NOT NULL, entitlement_requirements JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65C5981660E4B879 ON workflow (feature_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65C598169033212A ON workflow (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65C59816DE12AB56 ON workflow (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65C5981616FE72E1 ON workflow (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workflow.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workflow.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workflow.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE workflow_execution (id SERIAL NOT NULL, workflow_id INT NOT NULL, current_state_id INT NOT NULL, tenant_id INT NOT NULL, context JSON DEFAULT NULL, status INT NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FF094DBF2C7C2CBA ON workflow_execution (workflow_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FF094DBF98A046EB ON workflow_execution (current_state_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FF094DBF9033212A ON workflow_execution (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workflow_execution.started_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN workflow_execution.ended_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE acknowledgement ADD CONSTRAINT FK_118AB7B59033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE acknowledgement ADD CONSTRAINT FK_118AB7B53F4605C0 FOREIGN KEY (autocondat_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE acknowledgement ADD CONSTRAINT FK_118AB7B5EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audience ADD CONSTRAINT FK_FDCD94189033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audience_segment ADD CONSTRAINT FK_C5C2F52F848CC616 FOREIGN KEY (audience_id) REFERENCES audience (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audience_segment ADD CONSTRAINT FK_C5C2F52FDB296AAD FOREIGN KEY (segment_id) REFERENCES segment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audience_user ADD CONSTRAINT FK_8A619601848CC616 FOREIGN KEY (audience_id) REFERENCES audience (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audience_user ADD CONSTRAINT FK_8A619601A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle ADD CONSTRAINT FK_A57B32FD9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle ADD CONSTRAINT FK_A57B32FDDE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle ADD CONSTRAINT FK_A57B32FD16FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component ADD CONSTRAINT FK_5BE9E38F1FAD9D3 FOREIGN KEY (bundle_id) REFERENCES bundle (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component ADD CONSTRAINT FK_5BE9E3860E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component ADD CONSTRAINT FK_5BE9E38ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component ADD CONSTRAINT FK_5BE9E389033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component ADD CONSTRAINT FK_5BE9E38DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component ADD CONSTRAINT FK_5BE9E3816FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A1469033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calendar_event ADD CONSTRAINT FK_57FA09C9A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calendar_event ADD CONSTRAINT FK_57FA09C92344888A FOREIGN KEY (recurrence_rule_id) REFERENCES recurrance_rule (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calendar_event ADD CONSTRAINT FK_57FA09C9401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calendar_event ADD CONSTRAINT FK_57FA09C98DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE channel ADD CONSTRAINT FK_A2F98E479033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE consent_log ADD CONSTRAINT FK_301137293F4605C0 FOREIGN KEY (autocondat_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE deadline ADD CONSTRAINT FK_B74774F28DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule ADD CONSTRAINT FK_974464569033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule ADD CONSTRAINT FK_97446456EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule_segment ADD CONSTRAINT FK_779635D57E50BC8F FOREIGN KEY (delivery_rule_id) REFERENCES delivery_rule (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule_segment ADD CONSTRAINT FK_779635D5DB296AAD FOREIGN KEY (segment_id) REFERENCES segment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule_channel ADD CONSTRAINT FK_CDEE4EF77E50BC8F FOREIGN KEY (delivery_rule_id) REFERENCES delivery_rule (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule_channel ADD CONSTRAINT FK_CDEE4EF772F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD CONSTRAINT FK_D8698A76166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD CONSTRAINT FK_D8698A769033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_type ADD CONSTRAINT FK_93151B829033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feature ADD CONSTRAINT FK_1FD775669033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feature ADD CONSTRAINT FK_1FD77566DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feature ADD CONSTRAINT FK_1FD7756616FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feature_feature ADD CONSTRAINT FK_2EC0EE6F269F99DD FOREIGN KEY (feature_source) REFERENCES feature (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feature_feature ADD CONSTRAINT FK_2EC0EE6F3F7AC952 FOREIGN KEY (feature_target) REFERENCES feature (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE license ADD CONSTRAINT FK_5768F4199A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE license ADD CONSTRAINT FK_5768F41960E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE license ADD CONSTRAINT FK_5768F419ADF66B1A FOREIGN KEY (assigned_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_item ADD CONSTRAINT FK_D600F78F589157E FOREIGN KEY (monetization_policy_id) REFERENCES monetization_policy (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_item ADD CONSTRAINT FK_D600F789033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_item ADD CONSTRAINT FK_D600F78DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_item ADD CONSTRAINT FK_D600F7816FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_profile ADD CONSTRAINT FK_65B179139033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_profile ADD CONSTRAINT FK_65B17913DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_profile ADD CONSTRAINT FK_65B1791316FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_transaction ADD CONSTRAINT FK_487414949A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_transaction ADD CONSTRAINT FK_487414945769024E FOREIGN KEY (subscriptor_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_transaction ADD CONSTRAINT FK_487414949033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_transaction ADD CONSTRAINT FK_48741494DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_transaction ADD CONSTRAINT FK_4874149416FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE monetization_policy ADD CONSTRAINT FK_CAA56AE29033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE monetization_policy ADD CONSTRAINT FK_CAA56AE2DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE monetization_policy ADD CONSTRAINT FK_CAA56AE216FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA848CC616 FOREIGN KEY (audience_id) REFERENCES audience (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA2B1068DF FOREIGN KEY (linked_document_id) REFERENCES document (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF29033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF2EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_log ADD CONSTRAINT FK_ED15DF272F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_document ADD CONSTRAINT FK_E52701AD166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_document ADD CONSTRAINT FK_E52701ADC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_document ADD CONSTRAINT FK_E52701AD9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_document ADD CONSTRAINT FK_E52701ADDE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_document ADD CONSTRAINT FK_E52701AD16FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase ADD CONSTRAINT FK_A2E8DF559033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase ADD CONSTRAINT FK_A2E8DF55166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase ADD CONSTRAINT FK_A2E8DF556C4BD59 FOREIGN KEY (parent_phase_id) REFERENCES project_phase (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase ADD CONSTRAINT FK_A2E8DF559448F50E FOREIGN KEY (workflow_execution_id) REFERENCES workflow_execution (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_project_phase ADD CONSTRAINT FK_C7088B47CBFAAD8A FOREIGN KEY (project_phase_source) REFERENCES project_phase (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_project_phase ADD CONSTRAINT FK_C7088B47D21FFD05 FOREIGN KEY (project_phase_target) REFERENCES project_phase (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_user ADD CONSTRAINT FK_65B21055A4479A53 FOREIGN KEY (project_phase_id) REFERENCES project_phase (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_user ADD CONSTRAINT FK_65B21055A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_assignment ADD CONSTRAINT FK_9DFBD2629033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_assignment ADD CONSTRAINT FK_9DFBD26299091188 FOREIGN KEY (phase_id) REFERENCES project_phase (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_assignment ADD CONSTRAINT FK_9DFBD2623F4605C0 FOREIGN KEY (autocondat_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role ADD CONSTRAINT FK_57698A6A9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service ADD CONSTRAINT FK_E19D9AD29033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service ADD CONSTRAINT FK_E19D9AD216FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session ADD CONSTRAINT FK_D044D5D43F4605C0 FOREIGN KEY (autocondat_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE settlement_ledger ADD CONSTRAINT FK_529382E12FC0CB0F FOREIGN KEY (transaction_id) REFERENCES marketplace_transaction (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE state ADD CONSTRAINT FK_A393D2FB2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES workflow (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3F1FAD9D3 FOREIGN KEY (bundle_id) REFERENCES bundle (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3DD48320 FOREIGN KEY (parent_subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D39033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D316FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD CONSTRAINT FK_527EDB259448F50E FOREIGN KEY (workflow_execution_id) REFERENCES workflow_execution (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD CONSTRAINT FK_527EDB2559EC7D60 FOREIGN KEY (assignee_id) REFERENCES actor (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task ADD CONSTRAINT FK_527EDB251BA80DE3 FOREIGN KEY (project_task_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant ADD CONSTRAINT FK_4E59C462727ACA70 FOREIGN KEY (parent_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_segment ADD CONSTRAINT FK_A17B7519033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_segment ADD CONSTRAINT FK_A17B751DB296AAD FOREIGN KEY (segment_id) REFERENCES segment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_audit_log ADD CONSTRAINT FK_D51901289033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_audit_log ADD CONSTRAINT FK_D5190128828AD0A0 FOREIGN KEY (changed_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_config ADD CONSTRAINT FK_F5214B259033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_users ADD CONSTRAINT FK_B1349DD9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_users ADD CONSTRAINT FK_B1349DD3F4605C0 FOREIGN KEY (autocondat_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_users ADD CONSTRAINT FK_B1349DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA37E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA33414710B FOREIGN KEY (agent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transition ADD CONSTRAINT FK_F715A75A314988D9 FOREIGN KEY (sourcec_state_id) REFERENCES state (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transition ADD CONSTRAINT FK_F715A75ADF73ECA8 FOREIGN KEY (target_state_id) REFERENCES state (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transition ADD CONSTRAINT FK_F715A75A2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES workflow (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE translation_entry ADD CONSTRAINT FK_4CD1F51F9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE translation_entry ADD CONSTRAINT FK_4CD1F51FDE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE translation_entry ADD CONSTRAINT FK_4CD1F51F16FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trigger ADD CONSTRAINT FK_1A6B0F5D8BF1A064 FOREIGN KEY (transition_id) REFERENCES transition (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6499033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64916FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_segment ADD CONSTRAINT FK_408CEB6FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_segment ADD CONSTRAINT FK_408CEB6FDB296AAD FOREIGN KEY (segment_id) REFERENCES segment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow ADD CONSTRAINT FK_65C5981660E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow ADD CONSTRAINT FK_65C598169033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow ADD CONSTRAINT FK_65C59816DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow ADD CONSTRAINT FK_65C5981616FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow_execution ADD CONSTRAINT FK_FF094DBF2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES workflow (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow_execution ADD CONSTRAINT FK_FF094DBF98A046EB FOREIGN KEY (current_state_id) REFERENCES state (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow_execution ADD CONSTRAINT FK_FF094DBF9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE acknowledgement DROP CONSTRAINT FK_118AB7B59033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE acknowledgement DROP CONSTRAINT FK_118AB7B53F4605C0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE acknowledgement DROP CONSTRAINT FK_118AB7B5EF1A9D84
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audience DROP CONSTRAINT FK_FDCD94189033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audience_segment DROP CONSTRAINT FK_C5C2F52F848CC616
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audience_segment DROP CONSTRAINT FK_C5C2F52FDB296AAD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audience_user DROP CONSTRAINT FK_8A619601848CC616
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audience_user DROP CONSTRAINT FK_8A619601A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle DROP CONSTRAINT FK_A57B32FD9033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle DROP CONSTRAINT FK_A57B32FDDE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle DROP CONSTRAINT FK_A57B32FD16FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component DROP CONSTRAINT FK_5BE9E38F1FAD9D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component DROP CONSTRAINT FK_5BE9E3860E4B879
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component DROP CONSTRAINT FK_5BE9E38ED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component DROP CONSTRAINT FK_5BE9E389033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component DROP CONSTRAINT FK_5BE9E38DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bundle_component DROP CONSTRAINT FK_5BE9E3816FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calendar DROP CONSTRAINT FK_6EA9A1469033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calendar_event DROP CONSTRAINT FK_57FA09C9A40A2C8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calendar_event DROP CONSTRAINT FK_57FA09C92344888A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calendar_event DROP CONSTRAINT FK_57FA09C9401B253C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calendar_event DROP CONSTRAINT FK_57FA09C98DB60186
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE channel DROP CONSTRAINT FK_A2F98E479033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE consent_log DROP CONSTRAINT FK_301137293F4605C0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE deadline DROP CONSTRAINT FK_B74774F28DB60186
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule DROP CONSTRAINT FK_974464569033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule DROP CONSTRAINT FK_97446456EF1A9D84
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule_segment DROP CONSTRAINT FK_779635D57E50BC8F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule_segment DROP CONSTRAINT FK_779635D5DB296AAD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule_channel DROP CONSTRAINT FK_CDEE4EF77E50BC8F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE delivery_rule_channel DROP CONSTRAINT FK_CDEE4EF772F5A1AA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document DROP CONSTRAINT FK_D8698A76166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document DROP CONSTRAINT FK_D8698A769033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_type DROP CONSTRAINT FK_93151B829033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feature DROP CONSTRAINT FK_1FD775669033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feature DROP CONSTRAINT FK_1FD77566DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feature DROP CONSTRAINT FK_1FD7756616FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feature_feature DROP CONSTRAINT FK_2EC0EE6F269F99DD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feature_feature DROP CONSTRAINT FK_2EC0EE6F3F7AC952
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE license DROP CONSTRAINT FK_5768F4199A1887DC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE license DROP CONSTRAINT FK_5768F41960E4B879
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE license DROP CONSTRAINT FK_5768F419ADF66B1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_item DROP CONSTRAINT FK_D600F78F589157E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_item DROP CONSTRAINT FK_D600F789033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_item DROP CONSTRAINT FK_D600F78DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_item DROP CONSTRAINT FK_D600F7816FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_profile DROP CONSTRAINT FK_65B179139033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_profile DROP CONSTRAINT FK_65B17913DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_profile DROP CONSTRAINT FK_65B1791316FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_transaction DROP CONSTRAINT FK_487414949A1887DC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_transaction DROP CONSTRAINT FK_487414945769024E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_transaction DROP CONSTRAINT FK_487414949033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_transaction DROP CONSTRAINT FK_48741494DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marketplace_transaction DROP CONSTRAINT FK_4874149416FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE monetization_policy DROP CONSTRAINT FK_CAA56AE29033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE monetization_policy DROP CONSTRAINT FK_CAA56AE2DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE monetization_policy DROP CONSTRAINT FK_CAA56AE216FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA8DB60186
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA9033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA848CC616
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA2B1068DF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_log DROP CONSTRAINT FK_ED15DF29033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_log DROP CONSTRAINT FK_ED15DF2EF1A9D84
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_log DROP CONSTRAINT FK_ED15DF272F5A1AA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE9033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_document DROP CONSTRAINT FK_E52701AD166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_document DROP CONSTRAINT FK_E52701ADC33F7837
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_document DROP CONSTRAINT FK_E52701AD9033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_document DROP CONSTRAINT FK_E52701ADDE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_document DROP CONSTRAINT FK_E52701AD16FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase DROP CONSTRAINT FK_A2E8DF559033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase DROP CONSTRAINT FK_A2E8DF55166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase DROP CONSTRAINT FK_A2E8DF556C4BD59
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase DROP CONSTRAINT FK_A2E8DF559448F50E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_project_phase DROP CONSTRAINT FK_C7088B47CBFAAD8A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_project_phase DROP CONSTRAINT FK_C7088B47D21FFD05
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_user DROP CONSTRAINT FK_65B21055A4479A53
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_user DROP CONSTRAINT FK_65B21055A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_assignment DROP CONSTRAINT FK_9DFBD2629033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_assignment DROP CONSTRAINT FK_9DFBD26299091188
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_phase_assignment DROP CONSTRAINT FK_9DFBD2623F4605C0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role DROP CONSTRAINT FK_57698A6A9033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service DROP CONSTRAINT FK_E19D9AD29033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service DROP CONSTRAINT FK_E19D9AD2DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service DROP CONSTRAINT FK_E19D9AD216FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session DROP CONSTRAINT FK_D044D5D43F4605C0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE settlement_ledger DROP CONSTRAINT FK_529382E12FC0CB0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE state DROP CONSTRAINT FK_A393D2FB2C7C2CBA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D3F1FAD9D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D3DD48320
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D39033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D3DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D316FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP CONSTRAINT FK_527EDB259448F50E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP CONSTRAINT FK_527EDB2559EC7D60
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE task DROP CONSTRAINT FK_527EDB251BA80DE3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant DROP CONSTRAINT FK_4E59C462727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_segment DROP CONSTRAINT FK_A17B7519033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_segment DROP CONSTRAINT FK_A17B751DB296AAD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_audit_log DROP CONSTRAINT FK_D51901289033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_audit_log DROP CONSTRAINT FK_D5190128828AD0A0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_config DROP CONSTRAINT FK_F5214B259033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_users DROP CONSTRAINT FK_B1349DD9033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_users DROP CONSTRAINT FK_B1349DD3F4605C0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant_users DROP CONSTRAINT FK_B1349DDD60322AC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA37E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA33414710B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transition DROP CONSTRAINT FK_F715A75A314988D9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transition DROP CONSTRAINT FK_F715A75ADF73ECA8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transition DROP CONSTRAINT FK_F715A75A2C7C2CBA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE translation_entry DROP CONSTRAINT FK_4CD1F51F9033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE translation_entry DROP CONSTRAINT FK_4CD1F51FDE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE translation_entry DROP CONSTRAINT FK_4CD1F51F16FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trigger DROP CONSTRAINT FK_1A6B0F5D8BF1A064
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6499033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64916FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_segment DROP CONSTRAINT FK_408CEB6FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_segment DROP CONSTRAINT FK_408CEB6FDB296AAD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow DROP CONSTRAINT FK_65C5981660E4B879
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow DROP CONSTRAINT FK_65C598169033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow DROP CONSTRAINT FK_65C59816DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow DROP CONSTRAINT FK_65C5981616FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow_execution DROP CONSTRAINT FK_FF094DBF2C7C2CBA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow_execution DROP CONSTRAINT FK_FF094DBF98A046EB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE workflow_execution DROP CONSTRAINT FK_FF094DBF9033212A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE acknowledgement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actor
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE audience
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE audience_segment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE audience_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE bundle
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE bundle_component
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE business_rule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE calendar
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE calendar_event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE channel
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE consent_log
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE deadline
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE delivery_rule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE delivery_rule_segment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE delivery_rule_channel
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE document
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE feature
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE feature_feature
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE license
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE marketplace_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE marketplace_profile
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE marketplace_transaction
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE monetization_policy
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notification
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notification_log
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE permission
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_document
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_phase
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_phase_project_phase
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_phase_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_phase_assignment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE recurrance_rule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reset_password_request
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE segment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE service
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE session
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE settlement_ledger
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE state
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE subscription
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE task
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tenant
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tenant_segment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tenant_audit_log
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tenant_config
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tenant_users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ticket
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE transition
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE translation_entry
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE trigger
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_segment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE workflow
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE workflow_execution
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
