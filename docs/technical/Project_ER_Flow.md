# Unified Project ER-Flow Diagram - College Sports Management System

This document merges the **Database Structure (ER)** with the **System Flow & Condition Logic (If/Else)** into a single, cohesive technical blueprint.

---

## 🏗️ Project ER-Flow Design (with Condition Logic)
This diagram shows the journey of data through the system, including the critical decision points that control the flow.

```mermaid
graph TD
    %% Entities (Rectangles)
    U[users table]
    P[players table]
    S[sports_categories table]
    T[teams table]
    M[matches table]
    R[match_results table]
    C[certificates table]

    %% Condition Logic (Diamonds)
    C1{IF Auth Valid?}
    C2{IF Sport Active?}
    C3{IF Team Full?}
    C4{IF Match Finished?}
    C5{IF Winner Declared?}

    %% The Integrated Flow
    U --> C1
    C1 -- YES --> P
    C1 -- NO --> AuthError[Access Denied]

    P --> C2
    C2 -- YES --> S
    C2 -- NO --> RegError[Registration Closed]

    S --> C3
    C3 -- YES --> NewTeam[Create New Team]
    C3 -- NO --> T[Add to Existing Team]

    T --> M
    M --> C4
    C4 -- YES --> D6{Finalize}
    C4 -- NO --> Pending[Keep Scheduled]

    D6 --> R
    R --> C5
    C5 -- YES --> C
    C5 -- NO --> Tie[Log as Draw]

    %% Standard B&W Styling
    style C1 fill:#fff,stroke:#333
    style C2 fill:#fff,stroke:#333
    style C3 fill:#fff,stroke:#333
    style C4 fill:#fff,stroke:#333
    style C5 fill:#fff,stroke:#333
    style D6 fill:#fff,stroke:#333
```

---

## 📑 Detailed Flow & Logic Analysis

| Step | Data Source | Logic Condition (IF/ELSE) | Success Action | Failure/Alternate Action |
| :--- | :--- | :--- | :--- | :--- |
| **Auth** | `users` | **IF** `username/pass` matches | Grant access to `players` registry. | Return 401 Unauthorized error. |
| **Reg** | `players` | **IF** `sport_status` is 'active' | Proceed to `sports_categories`. | Show "Registration Closed" msg. |
| **Team** | `sports` | **IF** `members < max_players` | Update `team_players` table. | **ELSE** CREATE new entry in `teams`. |
| **Match** | `matches` | **IF** `match_status` is 'completed'| Trigger `match_results` update. | Keep status as 'scheduled'. |
| **Award** | `results` | **IF** `winner_id` is NOT NULL | Generate entry in `certificates`. | Log result as 'Draw/Tie'. |

---

## 🔍 Deep Analysis of the ER-Flow
1.  **Data Persistence**: Every "YES" path results in a `COMMIT` to the database (linked by PK/FK).
2.  **Relational Integrity**: 
    - `C1` ensures that no one can modify `players` without a valid `users.id`.
    - `C3` ensures that the `teams.sport_id` always matches the `sports_categories.id`.
3.  **Workflow Finality**: The flow ends at `certificates`, which is a "Read-Only" state triggered by the result of `C5`.

---
