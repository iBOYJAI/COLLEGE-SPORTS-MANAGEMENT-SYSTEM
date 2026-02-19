# Hybrid DFD-ER Flow Design - College Sports Management System

This diagram represents the system flow using **ER-style shapes** (Rectangles for Entities and Diamonds for Logic/Processes).

---

## 🏗️ System Flow (ER-Styled DFD)

```mermaid
graph TD
    %% ER-Style Shapes
    %% Entities = Rectangles [ ]
    %% Processes/Relationships = Diamonds { }
    %% Data Stores = Open Rectangles ( ( ) ) or specialized shapes

    subgraph External_Entities
        S[Student/Player]
        A[Admin/Staff]
    end

    %% Flow logic using ER Diamonds
    P1{Authenticates}
    P2{Registers}
    P3{Schedules}
    P4{Records}
    P5{Awarding}

    %% Data Stores (Entities in ER terms)
    D1[(User Records)]
    D2[(Player Profiles)]
    D3[(Sport & Team Data)]
    D4[(Match Ledger)]
    D5[(Performance Logs)]

    %% Connections
    A --> P1
    P1 --- D1
    
    S --> P2
    P2 --- D2
    
    A --> P3
    P3 --- D3
    P3 --- D4
    
    P3 --> P4
    P4 --- D4
    P4 --- D5
    
    D5 --> P5
    P5 --> S

    %% Styling to mimic ER color theme (if desired, but keeping to clean standard)
    style P1 fill:#fff,stroke:#333,stroke-width:2px
    style P2 fill:#fff,stroke:#333,stroke-width:2px
    style P3 fill:#fff,stroke:#333,stroke-width:2px
    style P4 fill:#fff,stroke:#333,stroke-width:2px
    style P5 fill:#fff,stroke:#333,stroke-width:2px
```

---

## 📖 Symbol Key (ER vs DFD)

| Shape | ER Meaning | DFD Meaning | Usage in this Diagram |
| :--- | :--- | :--- | :--- |
| **Rectangle `[ ]`** | Entity | External Agent | Represents **Who** is using the system (Student/Admin). |
| **Diamond `{ }`** | relationship | Process | Represents the **Action** or Logic being performed. |
| **Cylinder `[( )]`** | N/A | Data Store | Represents the **Database Tables** where data is kept. |

---

### **Flow Explanation:**
1.  **Student** initiates a **Registration** action (Diamond), which stores data in **Player Profiles**.
2.  **Admin** performs **Authentication** (Diamond) against **User Records**.
3.  **Admin** creates **Schedules** (Diamond), pulling from **Sport Data** and writing to the **Match Ledger**.
4.  Once a match is played, the system **Records** (Diamond) results into **Performance Logs**.
5.  Finally, the system initiates **Awarding** (Diamond) to send certificates back to the **Student**.
