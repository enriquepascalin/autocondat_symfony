**Autocondat7 Roadmap**

* [x] **Milestone: Tech Stack Definition**
  * [x] Select backend core (Symfony 7.3 + PHP 8.3)
  * [x] Select microservices tech (Go)
  * [x] Select frontend (React / React Native)
  * [x] Select infrastructure tools (Docker, Redis, RabbitMQ, PostgreSQL)
* [x] **Milestone: Infrastructure Design**
  * [x] Define Modular Monolith + Microservices architecture
  * [x] Setup folder/module conventions
  * [x] Tenant management system (TenantAwareTrait / Interface)
  * [x] Translation strategy with Redis + fallback logic
* [ ] **Milestone: Core Modules Implementation (Models, Services, Contracts, Traits)**
  * [ ] AuthenticationModule (JWT, SSO, MFA)
    * [x] Entities
    * [ ] Services
  * [ ] MultiTenancyModule
    * [x] Entities
    * [ ] Services
  * [ ] WorkflowModule (State machines, rules)
    * [x] Entities
    * [ ] Services
  * [ ] ProjectModule (Tasks, milestones)
    * [x] Entities
    * [ ] Services
  * [ ] NotificationModule
    * [x] Entities
    * [ ] Services
  * [ ] SupportModule (Ticketing)
    * [ ] Entities
    * [ ] Services
  * [ ] LocalizationModule 
    * [x] Entities
    * [x] Services
    * [x] Redis translation cache
    * [x] Google Translate fallback service (mocked)
    * [ ] `createFallbackEntry()` method usage in TranslationManager
    * [ ] Unit + Integration tests (100% coverage)
  * [ ] StorageManagementModule
    * [ ] Entities
    * [ ] Services
  * [ ] SubscriptionModule
    * [x] Entities
    * [ ] Services
  * [ ] CRMModule
    * [ ] Entities
    * [ ] Services
  * [ ] ERPModule
    * [ ] Entities
    * [ ] Services
  * [ ] CMSModule
    * [ ] Entities
    * [ ] Services
  * [ ] MarketingModule
    * [ ] Entities
    * [ ] Services
  * [ ] MarketplaceModule
    * [ ] Entities
    * [ ] Services
  * [ ] AIModule
    * [ ] Entities
    * [ ] Services
  * [ ] BIModule
    * [ ] Entities
    * [ ] Services
  * [ ] AuditTrailModule
    * [ ] Entities
    * [ ] Services
  * [ ] BackofficeModule
    * [ ] Entities
    * [ ] Services
  * [ ] FrontendPortalsModule
    * [ ] Entities
    * [ ] Services
  * [ ] IntegrationGatewayModule
    * [ ] Entities
    * [ ] Services
* [ ] **Milestone: Core Modules Implementation 2 (Controllers, API Platform EasyAdminCRUDS)**
  * [ ] EasyAdminBundle
      * [ ] Configure
      * [ ] Create Dashboard
      * [ ] Integrate Graphs
      * [ ] Define KPI Engine and Widgets
      * [ ] Create Traits, Services, Listeners
  * [ ] AuthenticationModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] MultiTenancyModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] WorkflowModule (State machines, rules)
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] ProjectModule (Tasks, milestones)
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] NotificationModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] SupportModule (Ticketing)
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] LocalizationModule 
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] StorageManagementModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] SubscriptionModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] CRMModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] ERPModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] CMSModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] MarketingModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] MarketplaceModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] AIModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] BIModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] AuditTrailModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] BackofficeModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] FrontendPortalsModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
  * [ ] IntegrationGatewayModule
    * [ ] Controllers + EasyAdminBundleCRUDs
    * [ ] Listeners + Automation
* [ ] **Milestone: Testing & QA**
  * [x] PHPUnit integration
  * [x] PHP CS Fixer
  * [x] PHP Stan
  * [x] PHP Insights
  * [ ] Fix test errors
  * [ ] Fix code quality errors
  * [ ] Increase Tools Level
  * [ ] TestHelper for mocking services (Redis, GoogleTranslate)
  * [ ] Coverage reports + CI linting
  * [ ] Integrate with CI/CD and create pipelines
* [ ] **Milestone: RabbitMQ Key Events**
  * [x] Decide events before controller layer
  * [ ] Implement event listeners for TranslationModule
  * [ ] Implement domain events per module
* [ ] **Milestone: API & Gateway**
  * [ ] Design internal API endpoints (REST/GraphQL)
  * [ ] Gateway integration with service discovery
* [ ] **Milestone: Frontend Integration**
  * [ ] Create starter React app with routing
  * [ ] Connect to Symfony API (auth + multi-tenant aware)
  * [ ] Component structure by module (e.g., Notifications, Support, Localization)
* [ ] **Milestone: First Implementation - Autocondat Cert**
  * [x] Select first 3 certifications (NOM035, NOM037, ISO 9001)
  * [ ] Define workflow template for each certification
  * [ ] Build UX for certification dashboard (Kanban, docs, progress)
  * [ ] Define billing model (monthly or per-certification)
* [ ] **Milestone: Documentation & Launch**
  * [ ] README.md + CONTRIBUTING.md
  * [ ] Architecture overview diagram
  * [ ] Deployment scripts + Docker Compose
  * [ ] First deployment (dev/staging)
* [ ] **Milestone: AI & BI Integration**
  * [ ] Plan usage of LLMs (recommendations, autofill, insights)
  * [ ] Define analytics dashboards (BIModule)
  * [ ] Integrate alert-based insights from workflows
* [ ] **Milestone: Create CMS for Tenants**
  * [ ] Entities
  * [ ] Infrastructure
  * [ ] Services
  * [ ] CRUD + Listeners
* [ ] **Milestone: Frontend**
  * [ ] Create React Site 
* [ ] **Milestone: Infrastructure and Cloud**
  * [ ] Containerize with Docker
  * [ ] Orchestrate with Kubernetes
  * [ ] Configure Cloud Services
  * [ ] Create Environments
