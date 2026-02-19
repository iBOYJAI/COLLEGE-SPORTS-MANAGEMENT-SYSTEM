# Data Flow Diagrams (DFD) - College Sports Management System

This document provides a clean and structured representation of data movement within the College Sports Management System.

---

## 🔹 Level 0: Context Diagram
The **Context Diagram** shows the system as a single process and its interactions with external entities.

```mermaid
graph LR
    %% External Entities
    S[Student / Player]
    A[Admin / Staff]

    %% System Process
    System((College Sports Management System))

    %% Data Flows
    S -- Registration Info --> System
    S -- Sports Selection --> System
    System -- Match Schedules --> S
    System -- Achievement Certificates --> S

    A -- System Login --> System
    A -- Management Data --> System
    System -- Performance Reports --> A
    System -- Activity Logs --> A
```

---

## 🔹 Level 1: System Overview
The **Level 1 DFD** breaks the system into major functional modules and identifies the primary data stores.

```mermaid
graph TD
    %% External Entities
    S[Student / Player]
    A[Admin / Staff]

    %% Processes
    P1((1.0 Authentication))
    P2((2.0 Profile Management))
    P3((3.0 Sports & Team Assets))
    P4((4.0 Match Operations))
    P5((5.0 Results & Awards))

    %% Data Stores
    D1[(Users DB)]
    D2[(Players DB)]
    D3[(Sports DB)]
    D4[(Matches DB)]
    D5[(Performance DB)]

    %% Logical Flows
    A -- Credentials --> P1
    P1 <--> D1

    S -- Personal Details --> P2
    P2 <--> D2

    A -- Sport/Team Setup --> P3
    P3 <--> D3

    A -- Scheduling --> P4
    P4 <--> D4
    P3 -- Team Rosters --> P4

    P4 -- Final Scores --> P5
    P5 <--> D5
    P5 -- Certificates --> S
```

---

## 🔹 Level 2: Detailed Match Processing
The **Level 2 DFD** provides a granular view of the **Match Operations (4.0)** process.

```mermaid
graph TD
    %% Admin Input
    A[Admin / Staff]

    %% Sub-processes
    P4_1((4.1 Assign Venue & Time))
    P4_2((4.2 Update Match Status))
    P4_3((4.3 Record Team Scores))
    P4_4((4.4 Calculate Winner))

    %% Data Stores
    D3[(Sports DB)]
    D4[(Matches DB)]
    D5[(Performance DB)]

    %% Execution Flows
    A -- Match Details --> P4_1
    D3 -- Availability --> P4_1
    P4_1 -- Save Schedule --> D4

    A -- Update Status --> P4_2
    P4_2 -- Status Change --> D4

    A -- Record Scores --> P4_3
    P4_3 -- Store Scores --> D4

    D4 -- Final Scores --> P4_4
    P4_4 -- Log Results --> D5
    P4_4 -- Update Standings --> D3
```

---

## 📊 Component Overview

| Component | Type | Description |
| :--- | :--- | :--- |
| **Student** | Entity | Primary user who participates in sports activities. |
| **Admin** | Entity | Authorized personnel managing the sports system. |
| **Processes** | Logic | Circles representing functional transformations of data. |
| **Data Stores** | Storage | Cylinders representing persistent database tables. |
| **Data Flows** | Movement | Arrows representing the direction of information travel. |

---

