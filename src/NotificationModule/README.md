Notification Module Architecture
================================

I. Introduction
---------------

The Notification Module provides enterprise-grade notification capabilities for Autocondat's SaaS platform. It handles the entire notification lifecycle - from creation and templating to intelligent routing, delivery, and tracking. Key features include:

*   Multi-channel delivery (email/SMS/push/webhook)
    
*   Circuit breaker patterns for fault tolerance
    
*   Full audit trails with delivery status tracking
    
*   Tenant isolation for data security
    
*   AI-driven routing optimization
    
*   Support for all seven industry applications
    

II. Core Architecture
---------------------

### II.1 High-Level System Flow

The notification processing follows a strict 5-phase architecture:

1.  **Trigger Phase**:
    
    *   Requests originate from API calls, scheduled jobs, or system events
        
    *   Supports immediate, scheduled, and event-triggered notifications
        
2.  **Orchestration Phase**:
    
    *   NotificationOrchestratorService validates payloads and resolves audiences
        
    *   Persists notification entities and dispatches lifecycle events
        
3.  **Processing Phase**:
    
    *   Asynchronous workers render content via Twig templates
        
    *   Applies business rules and delivery constraints
        
    *   AI-driven channel selection with failover mechanisms
        
4.  **Delivery Phase**:
    
    *   Transmits through channel-specific adapters (Email/SMS/Push)
        
    *   Implements circuit breaker patterns for fault tolerance
        
5.  **Audit Phase**:
    
    *   Immutable tracking of delivery status and costs
        
    *   Generates reconciliation reports
        

III. Component Architecture
---------------------------

### III.1 Orchestration Layer

*   **NotificationOrchestratorService**:
    
    *   Central coordinator handling payload validation
        
    *   Audience resolution and pipeline initiation
        
    *   Enforces tenant-specific delivery rules
        
    *   Permission checks and security validation
        
*   **NotificationLifecycleEvent**:
    
    *   Dispatched at critical stages:
        
        *   CREATED: After initial persistence
            
        *   PROCESSING: When pipeline starts
            
        *   DELIVERED: On successful delivery
            
        *   FAILED: After final delivery attempt
            
*   **EnqueueNotificationSubscriber**:
    
    *   Converts lifecycle events into prioritized queue messages
        
    *   Embeds tenant context in messages
        
    *   Applies payload compression
        

IV. Multi-Tenant Implementation
-------------------------------

### IV.1 Data Isolation Model

Tenant isolation is enforced through:

1.  **Database Layer**:
    
    *   All entities implement TenantAwareInterface
        
    *   Automatic tenant\_id injection in Doctrine queries
        
    *   Unique indexes on (tenant\_id, notification\_id)
        
2.  **Service Layer**:
    
    *   Redis keys prefixed with tenant\_{id}
        
    *   Queue workers tagged with tenant context
        
    *   Tenant-scoped circuit breakers
        
3.  **Hierarchy Support**:
    
    text
    
    Copy
    
    Download
    
    Company (default config)
      ├─ Branch (override config)
      └─ Team (audience segmentation)
    

V. Workflow Types
-----------------

### V.1 Immediate Notifications

*   **Processing Flow**:
    
    1.  API request received via NotificationApiController
        
    2.  Orchestrator validates and persists notification
        
    3.  Message enqueued in HIGH-priority queue
        
    4.  Pipeline executes within 500ms
        
    5.  Delivery through primary channel
        
*   **Performance SLA**:
    
    *   95th percentile: < 2 seconds
        
    *   99th percentile: < 5 seconds
        
*   **Use Cases**:
    
    *   Security alerts (login attempts)
        
    *   Transaction confirmations
        
    *   Emergency system notifications
        

### V.2 Scheduled Notifications

*   **Key Components**:
    
    *   Cron Scheduler: Symfony Command running hourly
        
    *   NotificationFinderService: Locates due notifications
        
    *   Debounce Check: Prevents duplicate deliveries
        
*   **Schedule Patterns**:
    
    Pattern
    
    Syntax Example
    
    One-time
    
    2025-07-15 14:30:00
    
    Recurring
    
    0 8 \* \* MON-FRI (Weekdays at 8 AM)
    
    Relative
    
    +3 days from trigger event
    

### V.3 Event-Triggered Notifications

*   **Integration Points**:
    
    1.  WorkflowModule state transitions
        
    2.  CRM lead status changes
        
    3.  AuditModule compliance violations
        
*   **Sample Subscription**:
    
    php
    
    Copy
    
    Download
    
    class ProjectEventSubscriber implements EventSubscriberInterface {
        public static function getSubscribedEvents(): array {
            return \[
                ProjectCompletedEvent::class \=> 'onProjectCompleted'
            \];
        }
        
        public function onProjectCompleted(ProjectCompletedEvent $event): void {
            $this\->notificationOrchestrator\->trigger(
                'project\_completed',
                $event\->getProject()\->getOwner()
            );
        }
    }
    

VI. Error Handling
------------------

### VI.1 Circuit Breaker Pattern

*   **State Management**:
    
    *   Closed State: Normal operations (requests pass through)
        
    *   Open State: Short-circuits requests after threshold breaches
        
    *   Half-Open: Allows trial requests after timeout period
        
*   **Configuration (per channel)**:
    
    yaml
    
    Copy
    
    Download
    
    \# config/packages/notification.yaml
    circuit\_breaker:
      email:
        failure\_threshold: 5
        reset\_timeout: 600 \# seconds
      sms:
        failure\_threshold: 3
        reset\_timeout: 300
    

### VI.2 Retry Mechanism

*   **Exponential Backoff Strategy**:
    
    Attempt
    
    Delay
    
    1
    
    0s (immediate)
    
    2
    
    30s
    
    3
    
    2m
    
    4
    
    10m
    
    5
    
    1h
    
*   **Dead Letter Handling**:
    
    *   After 4 failed attempts:
        
        *   Notification moved to Dead Letter Queue
            
        *   SupportModule ticket created
            
        *   Admin notification sent
            

VII. Security Model
-------------------

### VII.1 Data Protection

*   **Encryption**:
    
    *   Sensitive payloads encrypted with AES-256-GCM
        
    *   Tenant-specific encryption keys
        
*   **Template Safeguards**:
    
    twig
    
    Copy
    
    Download
    
    {# Automatic sanitization #}
    {{ user\_provided\_content|sanitize\_html }} 
    
    {# Sandboxed execution #}
    {% sandbox %}
        {% include user\_template %}
    {% endsandbox %}
    

### VII.2 Access Controls

*   **RBAC Matrix**:
    
    Permission
    
    Admin
    
    Manager
    
    User
    
    Send notification
    
    ✓
    
    ✓
    
    △
    
    Configure channels
    
    ✓
    
    △
    
    ✗
    
    View audit logs
    
    ✓
    
    ✓
    
    ✗
    
    △ = Tenant-scoped permissions
    

VIII. Monitoring
----------------

### VIII.1 Key Metrics

Metric

Description

Alert Threshold

Queue Depth

Pending messages

\> 1,000

Delivery Latency

Send-to-receive delay

\> 30s

Channel Error Rate

Per-channel failures

\> 5%/5min

Tenant Quota

Monthly allocation usage

\> 90%

### VIII.2 Grafana Dashboard

The monitoring dashboard provides:

*   Real-time delivery statistics
    
*   Channel health status indicators
    
*   Tenant usage breakdowns
    
*   Historical performance trends
    
*   Alert notifications for critical thresholds
    

IX. Integration Guide
---------------------

### IX.1 Sending Notifications

php

Copy

Download

public function sendPasswordReset(User $user): void {
    $this\->notificationOrchestrator\->createNotification(
        type: 'security',
        recipient: $user\->getEmail(),
        template: 'password\_reset',
        variables: \['token' \=> $this\->generateToken()\],
        tenant: $user\->getTenant()
    );
}

### IX.2 Adding New Channels

1.  Implement ChannelSenderInterface:
    
    php
    
    Copy
    
    Download
    
    class DiscordSender implements ChannelSenderInterface {
        public function send(Recipient $recipient, NotificationContent $content): DeliveryReport {
            // Channel-specific logic
        }
    }
    
2.  Register with tag:
    
    yaml
    
    Copy
    
    Download
    
    App\\NotificationModule\\Service\\DiscordSender:
        tags: \['notification.channel'\]
    

X. Appendix
-----------

### X.1 Performance Benchmarks

Scenario

100 Notifications

10,000 Notifications

Email

2.1s

41s

SMS

3.4s

68s

Push

1.8s

35s

_Tested on AWS t3.xlarge with Redis cluster_

### X.2 Troubleshooting Guide

Symptom

Likely Cause

Resolution

Notifications stuck in queue

Worker downtime

Restart messenger consumers

High failure rate

Provider API changes

Update channel adapter

Missing tenant data

Context serialization error

Verify TenantAwareTrait

* * *

\*© 2025 Autocondat - Notification System v3.1 | Confidential\*

Notification Module Architecture
================================

> **Version 3.1 – 2025**  
> Enterprise-grade, multi-tenant notification engine for the Autocondat SaaS platform  
> © 2025 Autocondat — Confidential

* * *

Table of Contents
-----------------

1.  [Introduction](#i-introduction)
    
2.  [Core Architecture](#ii-core-architecture)
    
3.  [Component Architecture](#iii-component-architecture)
    
4.  [Workflow Types](#iv-workflow-types)
    
5.  [Multi-Tenant Implementation](#v-multi-tenant-implementation)
    
6.  [Error Handling & Recovery](#vi-error-handling--recovery)
    
7.  [Security Model](#vii-security-model)
    
8.  [Monitoring & Observability](#viii-monitoring--observability)
    
9.  [Integration Guide](#ix-integration-guide)
    
10.  [Extension & Customisation](#x-extension--customisation)
    
11.  [Architectural Decisions](#xi-architectural-decisions)
    
12.  [Appendix](#xii-appendix)
    

* * *

I. Introduction
---------------

The **Notification Module** powers the entire notification lifecycle—creation → templating → routing → delivery → tracking—across all seven Autocondat industry applications.

### Key Capabilities

*   Multi-channel delivery: Email, SMS, Push, Webhook
    
*   AI-driven routing with circuit-breaker health checks
    
*   Strict tenant isolation via `TenantAwareInterface` and Doctrine filters
    
*   Full compliance: GDPR retention, audit trails, RBAC
    
*   Enterprise SLAs: 95-th %ile < 2 s, 99-th %ile < 5 s for immediate flows
    

* * *

II. Core Architecture
---------------------

### II.1 System Flow

\[DIAGRAM: core\_system\_flow\]

### II.2 Trigger Phase

*   API: `POST /notifications`
    
*   Scheduled jobs: Symfony Commands / cron
    
*   Domain events: Emitted by other Autocondat modules
    

### II.3 Orchestration Phase

*   Payload validation & audience resolution
    
*   Tenant context injection
    
*   Dispatches `NotificationLifecycleEvent`
    

### II.4 Processing Phase

*   Twig template rendering with XSS sanitisation
    
*   AI channel selection & cost optimisation
    
*   Circuit breaker health guard
    

* * *

III. Component Architecture
---------------------------

### III.1 Orchestration Layer

**NotificationOrchestratorService**

1.  Validate payload integrity
    
2.  Resolve audience segments via CRM integration
    
3.  Apply tenant-specific delivery rules
    
4.  Initiate tracking workflows
    

### III.2 Processing Services

**TemplateRendererService**  
\[DIAGRAM: template\_rendering\_flow\]

*   Supports `{{ user.name }}` placeholders
    
*   Automatic XSS sanitisation
    
*   Redis-cached compilation
    

**ChannelRouterService**

Factor

Weight

User Preference

35 %

Channel Health

25 %

Message Urgency

20 %

Cost Constraints

15 %

Compliance

5 %



# Notification Module Architecture  

## I. Introduction  
The Notification Module provides enterprise-grade notification capabilities for Autocondat's SaaS platform. It handles:  
- **Multi-channel delivery**: Email, SMS, Push, Webhook  
- **Lifecycle management**: Creation → Routing → Delivery → Tracking  
- **Tenant isolation**: Strict data separation for all 7 industry applications  
- **Compliance**: Audit trails, GDPR retention policies, and RBAC controls  

---

## II. Core Architecture  
### II.1 System Flow  
`[DIAGRAM: core_flow]`  
*Diagram Placeholder: High-level processing pipeline showing Trigger → Orchestration → Processing → Delivery → Audit phases*

1. **Trigger Phase**  
   - API endpoints (`POST /notifications`)  
   - Scheduled cron jobs  
   - Symfony events from other modules  

2. **Orchestration Phase**  
   - Payload validation and audience resolution  
   - Tenant context injection  
   - Lifecycle event dispatching  

3. **Processing Phase**  
   - Template rendering with dynamic variables  
   - AI-driven channel selection  
   - Circuit breaker health checks  

---

## III. Component Architecture  
### III.1 Orchestration Layer  
#### NotificationOrchestratorService  
- **Responsibilities**:  
  - Validate payload integrity  
  - Resolve audience segments via CRM integration  
  - Apply tenant-specific delivery rules  
  - Initiate tracking workflows  

### III.2 Processing Services  
#### TemplateRendererService  
`[DIAGRAM: template_rendering]`  
*Diagram Placeholder: Twig template → Variable injection → Content sanitization → Multi-channel output*

- **Features**:  
  - Supports `{{user.name}}` style placeholders  
  - Automatic XSS sanitization  
  - Redis-cached template compilation  

#### ChannelRouterService  
- **Routing logic weights**:  
  | Factor | Weight |  
  |--------|--------|  
  | User Preference | 35% |  
  | Channel Health | 25% |  
  | Message Urgency | 20% |  
  | Cost Constraints | 15% |  
  | Compliance | 5% |  

`[DIAGRAM: channel_failover]`  
*Diagram Placeholder: Primary → Secondary → Escalation channel cascade*

---

## IV. Multi-Tenant Implementation  
### IV.1 Isolation Model  
`[DIAGRAM: tenant_er_model]`  
*Diagram Placeholder: Entity-Relationship diagram showing Tenant ↔ Notification ↔ ChannelConfig*

#### Enforcement Mechanisms:  
1. **Database Layer**  
   - `TenantAwareInterface` on all entities  
   - Doctrine filter auto-injects `WHERE tenant_id = ?`  
2. **Service Layer**  
   - Redis key prefix: `tenant_{id}_notification`  
   - Tenant-scoped circuit breakers  
3. **Hierarchy Support**  
   ```plaintext
   Company (default config)
     ├─ Branch (override config)
     └─ Team (audience segmentation)

## V. Workflow Types  
### V.1 Immediate Notifications  
`[DIAGRAM: immediate_flow]`  
*Diagram Placeholder: Linear flow - API → Orchestrator → HIGH_QUEUE → Pipeline → Delivery*

- **Processing Flow**:  
  1. API request received via `NotificationApiController`  
  2. Orchestrator validates and persists notification  
  3. Message enqueued in HIGH-priority queue  
  4. Pipeline executes within 500ms  
  5. Delivery through primary channel  

- **Performance SLA**:  
  - 95th percentile: < 2 seconds  
  - 99th percentile: < 5 seconds  

- **Use Cases**:  
  - Security alerts (login attempts)  
  - Transaction confirmations  
  - Emergency system notifications  

### V.2 Scheduled Notifications  
`[DIAGRAM: scheduled_flow]`  
*Diagram Placeholder: Clock icon → Scheduler → BATCH_QUEUE → Pipeline → Delivery*

- **Key Components**:  
  - **Cron Scheduler**: Symfony Command running hourly  
  - **NotificationFinderService**: Locates due notifications  
  - **Debounce Check**: Prevents duplicate deliveries  

- **Schedule Patterns**:  
  | Pattern | Syntax Example |  
  |---------|---------------|  
  | One-time | `2025-07-15 14:30:00` |  
  | Recurring | `0 8 * * MON-FRI` (Weekdays at 8 AM) |  
  | Relative | `+3 days` from trigger event |  

- **Use Cases**:  
  - Daily digest emails  
  - Payment reminders  
  - Certification expiry alerts  

### V.3 Event-Triggered Notifications  
`[DIAGRAM: event_flow]`  
*Diagram Placeholder: Lightning bolt → EventDispatcher → WORKFLOW_QUEUE → Pipeline*

- **Integration Points**:  
  1. WorkflowModule state transitions  
  2. CRM lead status changes  
  3. AuditModule compliance violations  

- **Sample Subscription**:  
  ```php
  class ProjectEventSubscriber implements EventSubscriberInterface {
      public static function getSubscribedEvents(): array {
          return [
              ProjectCompletedEvent::class => 'onProjectCompleted'
          ];
      }
      
      public function onProjectCompleted(ProjectCompletedEvent $event): void {
          $this->notificationOrchestrator->trigger(
              'project_completed',
              $event->getProject()->getOwner()
          );
      }
  }


## VI. Error Handling  
The Notification Module implements robust error recovery mechanisms to ensure reliable message delivery while maintaining system stability.  

### VI.1 Circuit Breaker Pattern  
`[DIAGRAM: circuit_breaker_states]`  
*Diagram Placeholder: State machine showing Closed → Open → Half-Open transitions*

#### Implementation:  
1. **State Management**:  
   - **Closed State**: Normal operations (requests pass through)  
   - **Open State**: Short-circuits requests after threshold breaches  
   - **Half-Open**: Allows trial requests after timeout period  

2. **Configuration (per channel)**:  
   ```yaml
   notification:
     circuit_breaker:
       email:
         failure_threshold: 5    # Max failures before tripping
         reset_timeout: 600       # Seconds before Half-Open state
         trial_requests: 2        # Test requests in Half-Open state
       sms:
         failure_threshold: 3
         reset_timeout: 300

3. **Monitoring**:
  -  Redis tracks failure counts per tenant-channel combination
  -  Prometheus metric: circuit_breaker_state{channel="email"}
        - 0 = Closed, 1 = Open, 2 = Half-Open

VI.2 Retry Mechanism
Staged Recovery Strategy:

    Immediate Retry:

        For transient errors (timeouts, rate limits)

        First retry after 10 seconds

    Exponential Backoff:
    Attempt	Delay	Conditions
    1	10s	All errors
    2	1m	Non-critical errors
    3	5m	Non-critical errors
    4	15m	All errors

    Dead Letter Handling:

        After 4 failed attempts:


graph LR
    Failed --> DLQ[Dead Letter Queue]
    DLQ --> Support[SupportModule Ticket]
    DLQ --> Alert[Admin Notification]


VI.3 Reconciliation System
Automated Recovery Flow:

    Scheduled Scans:

        Every 30 minutes for PENDING notifications

        Every 24 hours for RECONCILE_REQUIRED state

    Provider Status Checks:

public function reconcile(Notification $notification): void {
    $provider = $this->providerResolver->get($notification->getChannel());
    $status = $provider->getDeliveryStatus($notification->getExternalId());
    
    $this->tracker->updateStatus($notification, $status);
}

    Escalation Path:
    Unresolved Time	Action
    > 1 hour	Retry through secondary channel
    > 24 hours	Create SupportModule ticket
    > 72 hours	Alert system administrators

VI.4 Alerting Integration
Notification Failure Events:

    CircuitBreakerTrippedEvent:

        Sent to SupportModule when channel disabled

        Includes: Tenant ID, channel, failure count, last error

    NotificationPermanentlyFailedEvent:

        Triggers after dead letter queue placement

        Payload contains:

{
  "notification_id": "uuid",
  "tenant_id": "tenant_123",
  "failure_reasons": ["TIMEOUT", "INVALID_RECIPIENT"],
  "final_delivery_attempt": "2025-07-15T14:30:00Z"
}

ReconciliationRequiredEvent:

    When provider status check fails

    Triggers manual review workflow

VI. Error Handling
VI.1 Circuit Breaker Pattern

[DIAGRAM: circuit_breaker_states]
Diagram Placeholder: State machine - Closed ↔ Open ↔ Half-Open

    Threshold Configuration:
    yaml

    # config/packages/notification.yaml
    circuit_breaker:
      email:
        failure_threshold: 5
        reset_timeout: 600 # seconds
      sms:
        failure_threshold: 3
        reset_timeout: 300

VI.2 Retry Mechanism

    Exponential Backoff Strategy:
    Attempt	Delay
    1	0s (immediate)
    2	30s
    3	2m
    4	10m
    5	1h

    Dead Letter Queue:

        Failed notifications after max attempts

        Manual review in SupportModule dashboard

VII. Security Model
VII.1 Data Protection

    Encryption:

        Sensitive payloads encrypted with AES-256-GCM

        Tenant-specific encryption keys

    Template Safeguards:
    twig

    {# Automatic sanitization #}
    {{ user_provided_content|sanitize_html }} 

    {# Sandboxed execution #}
    {% sandbox %}
        {% include user_template %}
    {% endsandbox %}

VII.2 Access Controls

    RBAC Matrix:
    Permission	Admin	Manager	User
    Send notification	✓	✓	△
    Configure channels	✓	△	✗
    View audit logs	✓	✓	✗

    △ = Tenant-scoped permissions

VIII. Monitoring
VIII.1 Key Metrics
Metric	Prometheus Name	Alert Threshold
Queue Depth	notification_queue_messages	> 1,000
Delivery Latency	notification_delivery_latency_seconds	p99 > 30s
Error Rate	notification_error_rate	> 5%/5min
Tenant Quota	tenant_notification_quota_used	> 90%
VIII.2 Grafana Dashboard

[DIAGRAM: grafana_dashboard]
Diagram Placeholder: Mock dashboard showing delivery stats, channel health, and tenant usage
IX. Integration Guide
IX.1 Sending Notifications
php

// Sample service integration
public function sendPasswordReset(User $user): void {
    $this->notificationOrchestrator->createNotification(
        type: 'security',
        recipient: $user->getEmail(),
        template: 'password_reset',
        variables: ['token' => $this->generateToken()],
        tenant: $user->getTenant()
    );
}

IX.2 Adding New Channels

    Implement ChannelSenderInterface:
    php

class DiscordSender implements ChannelSenderInterface {
    public function send(Recipient $recipient, NotificationContent $content): DeliveryReport {
        // Channel-specific logic
    }
}

Register with tag:
yaml

    App\NotificationModule\Service\DiscordSender:
        tags: ['notification.channel']

X. Appendix
X.1 Performance Benchmarks
Scenario	100 Notifications	10,000 Notifications
Email	2.1s	41s
SMS	3.4s	68s
Push	1.8s	35s

Tested on AWS t3.xlarge with Redis cluster
X.2 Troubleshooting Guide
Symptom	Likely Cause	Resolution
Notifications stuck in queue	Worker downtime	Restart messenger consumers
High failure rate	Provider API changes	Update channel adapter
Missing tenant data	Context serialization error	Verify TenantAwareTrait

*© 2025 Autocondat - Notification System v3.1 | Confidential*




# Notification Module

## I. Introduction

The Notification Module provides enterprise-grade notification capabilities for Autocondat's SaaS platform. It handles the entire notification lifecycle - from creation and templating to intelligent routing, delivery, and tracking. Built on Symfony's event-driven architecture, it supports:  
- Multi-channel delivery (email/SMS/push/webhook)  
- Circuit breaker patterns for fault tolerance  
- Full audit trails with delivery status tracking  
- Tenant isolation for data security  
- AI-driven routing optimization  

The module serves all seven industry applications with customizable workflows while maintaining strict compliance with enterprise security standards.  

## II. Core Architecture

### II.1 High-Level System 

```mermaid
graph TD
    A[API/Event Trigger] --> B(NotificationOrchestrator)
    B --> C[Persist Notification]
    B --> D[Dispatch Lifecycle Event]
    D --> E[Message Queue]
    E --> F[Delivery Pipeline]
    F --> G[Content Rendering]
    F --> H[Channel Routing]
    H --> I[Circuit Breaker Check]
    H --> J[Channel Delivery]
    J --> K[Tracking Service]
    K --> L[Audit Log]


## II.2 Component Interactions
##  III. Key Components
##  III.1 Orchestration Layer
##  III.2 Processing Pipeline
##  III.3 Channel Management
##  III.4 Tracking & Audit
## IV. Workflow Types
##  IV.1 Immediate Notifications
##  IV.2 Scheduled Notifications
##  IV.3 Event-Triggered Notifications
## V. Multi-Tenant Implementation
## VI. Key Features

# Notification Module Architecture

## Overview
The Notification Module provides enterprise-grade notification capabilities for Autocondat's SaaS platform. It handles the entire notification lifecycle - from creation and templating to intelligent routing, delivery, and tracking. Built on Symfony's event-driven architecture, it supports multi-channel delivery (email/SMS/push), circuit breaker patterns, and full auditability.

---

## Core Architecture
```mermaid
graph TD
    A[API/Event Trigger] --> B(NotificationOrchestrator)
    B --> C[Persist Notification]
    B --> D[Dispatch Lifecycle Event]
    D --> E[Message Queue]
    E --> F[Delivery Pipeline]
    F --> G[Content Rendering]
    F --> H[Channel Routing]
    H --> I[Circuit Breaker Check]
    H --> J[Channel Delivery]
    J --> K[Tracking Service]
    K --> L[Audit Log]

Key Components
1. Orchestration Layer

    NotificationOrchestratorService: Central coordinator for notification processing

    NotificationLifecycleEvent: Event dispatched at critical lifecycle stages

    EnqueueNotificationSubscriber: Converts events to queued messages

2. Processing Pipeline
sequenceDiagram
    participant Pipeline
    participant Renderer
    participant Router
    participant CircuitBreaker
    participant Sender
    participant Tracker
    
    Pipeline->>Renderer: renderContent()
    Pipeline->>Router: routeNotification()
    Router->>CircuitBreaker: checkState(channel)
    Router->>Sender: send()
    Sender-->>Router: DeliveryReport
    Router-->>Pipeline: Result
    Pipeline->>Tracker: recordDelivery()

    3. Channel Management

    ChannelRouterService: AI-driven optimal channel selection

    NotificationCircuitBreaker: Monitors channel health

    ChannelSenderInterface: Contract for channel implementations

4. Tracking & Audit

    NotificationTrackerService: Real-time delivery monitoring

    DeliveryReport: Standardized status container

    NotificationLog: Entity for permanent audit records


Workflow Types
1. Immediate Notifications

graph LR
    API-->Orchestrator-->Queue-->Pipeline-->Delivery

2. Scheduled Notifications

graph LR
    Scheduler-->Orchestrator-->Queue-->Pipeline

3. Event-Triggered

graph LR
    SystemEvent-->LifecycleEvent-->Queue-->Pipeline


Multi-Tenant Implementation

erDiagram
    TENANT ||--o{ NOTIFICATION : "1 to Many"
    TENANT ||--o{ CHANNEL : "Owns"
    TENANT ||--o{ TEMPLATE : "Manages"
    CHANNEL ||--|{ PROVIDER : "Uses"

Key Features

    Dynamic channel failover

    Template personalization with Twig

    Delivery priority queues

    Real-time delivery tracking

    Circuit breaker pattern

    Multi-tenant isolation



#### 2. **ADVANCED.md** - Error Handling & Recovery
```markdown
# Advanced Notification Flows

## Failure Recovery System
```mermaid
graph TD
    Failure[Delivery Failure] --> Analyze{Analyze Failure}
    Analyze -->|Transient| Retry[Retry Subscriber]
    Analyze -->|Permanent| Escalate[Escalation Subscriber]
    Analyze -->|Channel Down| Circuit[Circuit Breaker]
    Retry -->|Immediate| Pipeline[Re-enter Pipeline]
    Retry -->|Delayed| Queue[Delayed Queue]
    Escalate --> AltChannel[Alternative Channel]
    Circuit -->|Trip| Disable[Disable Channel]
    Circuit -->|Reset| Monitor[Continue Monitoring]

Circuit Breaker Logic
stateDiagram-v2
    [*] --> Closed
    Closed --> Open: Failure threshold exceeded
    Open --> HalfOpen: Reset timeout
    HalfOpen --> Open: Trial failure
    HalfOpen --> Closed: Trial success

Retry Mechanism
gantt
    title Exponential Backoff Retry Sequence
    dateFormat  S
    axisFormat %S
    
    section Attempt 1
    Delivery : 0, 1s
    Failure : 1, 1
    
    section Attempt 2
    Retry : after 1s, 2s
    Failure : 3, 1
    
    section Attempt 3
    Retry : after 4s, 4s
    Success : 8, 1

Reconciliation Process

flowchart TB
    Scheduler[Reconciliation Scheduler] --> Checker[Status Checker]
    Checker -->|Pending| Provider[Query Provider API]
    Checker -->|Expired| Escalate[Create Support Ticket]
    Provider --> Updater[Status Updater]
    Updater --> Tracker[Notification Tracker]


Alerting Integration
sequenceDiagram
    CircuitBreaker->>SupportModule: ChannelDisabledEvent
    SupportModule->>TicketService: createTicket()
    TicketService->>NotificationModule: notifyAdmins()
    NotificationModule-->>SupportModule: TicketCreatedConfirmation


#### 3. **DEVELOPER_GUIDE.md** - Extension & Customization
```markdown
# Developer Guide

## Adding New Channels
```mermaid
flowchart TB
    Step1[Implement ChannelSenderInterface] --> Step2[Register Service]
    Step2 --> Step3[Tag with 'notification.channel']
    Step3 --> Step4[Configure in Tenant UI]


Testing Sequence
sequenceDiagram
    Tester->>TestRunner: php bin/console notification:test
    TestRunner->>MockSender: sendTestNotification()
    MockSender-->>TestRunner: DeliveryReport
    TestRunner->>Validator: verifyDelivery()
    Validator-->>Tester: Test Report

Debugging Flow

graph TD
    Issue[Reported Issue] --> LogCheck{Check NotificationLog}
    LogCheck -->|Missing Entry| Queue[Inspect Message Queue]
    LogCheck -->|Failed Status| Trace[Delivery Trace]
    Trace --> Provider[Provider Integration]
    Trace --> Circuit[Circuit Breaker State]
    Trace --> Template[Template Rendering]

Performance Optimization
gantt
    title Optimization Pipeline
    dateFormat  YYYY-MM-DD
    section Batching
    Implement Message Bundling :2023-10-01, 5d
    Test Throughput :2023-10-06, 2d
    
    section Caching
    Template Cache :2023-10-08, 3d
    Channel Selection Cache :2023-10-11, 2d
    
    section Parallelization
    Async Rendering :2023-10-13, 4d

Monitoring Points
graph LR
    Prometheus --> A[Queue Depth]
    Prometheus --> B[Delivery Latency]
    Prometheus --> C[Failure Rate]
    Prometheus --> D[Circuit Breaker Status]
    Grafana --> E[Dashboard Visualization]
    AlertManager --> F[Slack/Email Alerts]


#### 4. **DECISIONS.md** - Architectural Choices
```markdown
# Architectural Decisions

## Core Design Principles
```mermaid
mindmap
  root((Principles))
    Decoupled Components
    Event-Driven
    Tenant Isolation
    Extensible Channels
    Observable System
    Resilient Delivery

Tradeoff Analysis

quadrantChart
    title Decision Quadrants
    x-axis Urgency --> Impact
    y-axis Complexity --> Value
    quadrant-1 High Value/Low Effort
    quadrant-2 High Value/High Effort
    quadrant-3 Low Value/Low Effort
    quadrant-4 Low Value/High Effort
    
    “Circuit Breaker”: [0.8, 0.7]
    “AI Routing”: [0.9, 0.9]
    “SMS Fallback”: [0.6, 0.4]
    “PDF Attachments”: [0.3, 0.8]

Evolution Timeline    
timeline
    title Notification Module Evolution
    section 2023 Q3
      Core Pipeline : Message Queue
      Basic Channels : Email/SMS
      
    section 2023 Q4
      Circuit Breaker
      Template Engine
      Tracking Service
      
    section 2024 Q1
      AI Routing
      Provider Webhooks
      Multi-Channel ACK
      
    section Future
      Predictive Delivery
      Channel Auto-Discovery
      Blockchain Receipts

Dependency Map

flowchart TD
    NotificationModule --> WorkflowModule[State Machines]
    NotificationModule --> CRMModule[Audience Groups]
    NotificationModule --> BillingModule[Cost Tracking]
    NotificationModule --> AIModule[Routing AI]
    NotificationModule --> AuditModule[Change Logs]
    
    classDef required fill:#f96,stroke:#333;
    classDef optional fill:#6f9,stroke:#333;
    
    class WorkflowModule,CRMModule required
    class BillingModule,AIModule,AuditModule optional


Risk Mitigation

graph LR
    Risk1[Provider API Changes] --> Mitigation1[Adapter Pattern]
    Risk2[Lost Messages] --> Mitigation2[Dead Letter Queue]
    Risk3[Tenant Isolation] --> Mitigation3[TenantAware Trait]
    Risk4[Delivery Spikes] --> Mitigation4[Queue Prioritization]
    Risk5[Template Injection] --> Mitigation5[Sandboxed Rendering]


### How to Use:
1. Create these files in your `src/NotificationModule/docs/` directory:
   - `README.md`
   - `ADVANCED.md`
   - `DEVELOPER_GUIDE.md`
   - `DECISIONS.md`

2. Commit with message: "Add comprehensive module documentation"

3. View rendered diagrams on:
   - GitHub/GitLab: Native Mermaid support
   - VS Code: With Mermaid Preview extension
   - Local: Use `mermaid-cli` to generate images

### Benefits:
- **Living Documentation**: Diagrams stay current with code changes
- **Onboarding**: New developers understand flows in minutes
- **Decision Tracking**: Record architectural choices
- **Troubleshooting**: Visual debugging guides
- **Extension Guide**: Clear path for adding new features

Would you like me to generate documentation for other modules using this same pattern?




## Core Notification Flow

```mermaid
sequenceDiagram
    participant Client
    participant APIController
    participant Orchestrator
    participant EventDispatcher
    participant MessageBus
    participant Pipeline
    participant ChannelRouter
    participant Sender
    participant Tracker

    Client->>APIController: POST /notifications
    APIController->>Orchestrator: createNotification()
    Orchestrator->>EventDispatcher: dispatch(LifecycleEvent)
    EventDispatcher->>MessageBus: enqueue(SendNotificationMessage)
    MessageBus->>Pipeline: executeDeliveryPipeline()
    Pipeline->>ChannelRouter: route()
    ChannelRouter->>Sender: send()
    Sender-->>ChannelRouter: DeliveryReport
    ChannelRouter->>Tracker: recordDelivery()
    Tracker-->>Pipeline: Result
    Pipeline-->>MessageBus: Completion

    Failure Recovery Mechanism
    graph TD
    Failure[Delivery Failure] --> CheckPolicy{Check Retry Policy}
    CheckPolicy -->|Immediate Retry| Immediate[RetrySubscriber]
    CheckPolicy -->|Delayed Retry| Delayed[RabbitMQ Delayed Queue]
    CheckPolicy -->|Escalate| Escalation[PriorityEscalationSubscriber]
    Immediate --> Pipeline[Re-enter Pipeline]
    Delayed --> Pipeline
    Escalation --> AltChannel[Alternative Channel]
    AltChannel --> Pipeline

    Component Interactions
    flowchart TB
    subgraph Core Services
        Orchestrator(OrchestratorService)
        Pipeline(PipelineService)
        Router(ChannelRouter)
    end
    
    subgraph Support Components
        Tracker(TrackerService)
        Circuit(CircuitBreaker)
        Renderer(TemplateRenderer)
    end
    
    Orchestrator -->|Initiate| Pipeline
    Pipeline -->|Render Content| Renderer
    Pipeline -->|Route Channel| Router
    Router -->|Check Health| Circuit
    Router -->|Send| Sender[[External Senders]]
    Sender -->|Report| Tracker

    Multi-Tenant Isolation
    erDiagram
    TENANT ||--o{ NOTIFICATION : scopes
    TENANT ||--o{ CHANNEL : owns
    TENANT ||--o{ AUDIENCE : manages
    NOTIFICATION ||--o{ DELIVERY_LOG : generates
    CHANNEL ||--o{ DELIVERY_LOG : used_by

