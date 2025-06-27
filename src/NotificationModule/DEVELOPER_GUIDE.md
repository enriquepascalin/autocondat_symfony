
#### 3. **DEVELOPER_GUIDE.md** - Extension & Customization
# Developer Guide

## Adding New Channels
```mermaid
flowchart TB
    Step1[Implement ChannelSenderInterface] --> Step2[Register Service]
    Step2 --> Step3[Tag with 'notification.channel']
    Step3 --> Step4[Configure in Tenant UI]
```

Testing Sequence
```mermaid
sequenceDiagram
    Tester->>TestRunner: php bin/console notification:test
    TestRunner->>MockSender: sendTestNotification()
    MockSender-->>TestRunner: DeliveryReport
    TestRunner->>Validator: verifyDelivery()
    Validator-->>Tester: Test Report
```

Debugging Flow

```mermaid
graph TD
    Issue[Reported Issue] --> LogCheck{Check NotificationLog}
    LogCheck -->|Missing Entry| Queue[Inspect Message Queue]
    LogCheck -->|Failed Status| Trace[Delivery Trace]
    Trace --> Provider[Provider Integration]
    Trace --> Circuit[Circuit Breaker State]
    Trace --> Template[Template Rendering]
```

Performance Optimization
```mermaid
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
```

Monitoring Points
```mermaid
graph LR
    Prometheus --> A[Queue Depth]
    Prometheus --> B[Delivery Latency]
    Prometheus --> C[Failure Rate]
    Prometheus --> D[Circuit Breaker Status]
    Grafana --> E[Dashboard Visualization]
    AlertManager --> F[Slack/Email Alerts]
```

#### 4. **DECISIONS.md** - Architectural Choices
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
```

Tradeoff Analysis

```mermaid
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
```

Evolution Timeline    

```mermaid
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
```

Dependency Map

```mermaid
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
```

Risk Mitigation

```mermaid
graph LR
    Risk1[Provider API Changes] --> Mitigation1[Adapter Pattern]
    Risk2[Lost Messages] --> Mitigation2[Dead Letter Queue]
    Risk3[Tenant Isolation] --> Mitigation3[TenantAware Trait]
    Risk4[Delivery Spikes] --> Mitigation4[Queue Prioritization]
    Risk5[Template Injection] --> Mitigation5[Sandboxed Rendering]
```

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
```

Failure Recovery Mechanism
```mermaid
graph TD
    Failure[Delivery Failure] --> CheckPolicy{Check Retry Policy}
    CheckPolicy -->|Immediate Retry| Immediate[RetrySubscriber]
    CheckPolicy -->|Delayed Retry| Delayed[RabbitMQ Delayed Queue]
    CheckPolicy -->|Escalate| Escalation[PriorityEscalationSubscriber]
    Immediate --> Pipeline[Re-enter Pipeline]
    Delayed --> Pipeline
    Escalation --> AltChannel[Alternative Channel]
    AltChannel --> Pipeline
```

Component Interactions
```mermaid
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
```

Multi-Tenant Isolation
```mermaid
erDiagram
    TENANT ||--o{ NOTIFICATION : scopes
    TENANT ||--o{ CHANNEL : owns
    TENANT ||--o{ AUDIENCE : manages
    NOTIFICATION ||--o{ DELIVERY_LOG : generates
    CHANNEL ||--o{ DELIVERY_LOG : used_by
```
