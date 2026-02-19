# Deep Analysis ER & Logic Diagram - College Sports Management System

This document provides a deep analysis of the database architecture, including PK/FK mappings, conditional logic (if/else), and the complete system workflow.

---

## 🏗️ Deep Analysis ER Diagram (Physical Schema)
This diagram maps the actual database tables with their Primary Keys (PK) and Foreign Keys (FK).

```mermaid
erDiagram
    users ||--o{ activity_log : "logs(user_id)"
    users ||--o{ certificates : "issues(generated_by)"
    
    players ||--o{ player_sports : "maps(player_id)"
    sports_categories ||--o{ player_sports : "maps(sport_id)"
    
    sports_categories ||--o{ teams : "contains(sport_id)"
    teams ||--o{ team_players : "enrolls(team_id)"
    players ||--o{ team_players : "joins(player_id)"
    
    sports_categories ||--o{ matches : "organizes(sport_id)"
    teams ||--o{ matches : "participates(team1_id/team2_id)"
    
    matches ||--o| match_results : "finalized(match_id)"
    teams ||--o{ match_results : "wins(winner_team_id)"
    
    matches ||--o{ player_performance : "tracks(match_id)"
    players ||--o{ player_performance : "achieves(player_id)"
    
    players ||--o{ certificates : "receives(player_id)"
    sports_categories ||--o{ certificates : "categorized(sport_id)"

    users {
        int id PK
        string role "admin|staff"
        string status "active|inactive"
    }

    players {
        int id PK
        string register_number UK
        string status "active|inactive"
    }

    matches {
        int id PK
        int team1_id FK
        int team2_id FK
        string status "scheduled|completed|cancelled"
    }

    match_results {
        int id PK
        int match_id FK
        int winner_team_id FK
    }
```

---

## 🔄 Complete System Workflow & Logic Flow
This diagram shows the **Deep Analysis** of how data moves between tables with **Condition Checks (If/Else)**.

```mermaid
graph TD
    %% Entities & Logic
    U[Admin/Staff Session] --> Login{Check roles/status}
    
    %% Condition Check 1
    Login -- If active/admin --> P_Reg[Access players table]
    Login -- If inactive --> AccessDenied[Show Error]

    %% Player Workflow
    P_Reg --> LinkSport{Check sports_categories}
    LinkSport -- If valid --> P_Sport[Update player_sports table]
    
    %% Team Workflow
    P_Sport --> TeamCheck{Check team capacity}
    TeamCheck -- If space exists --> TeamJoin[Update team_players table]
    TeamCheck -- If full --> NewTeam[Create new team record]

    %% Match Workflow
    TeamJoin --> MatchSched[Update matches table]
    MatchSched --> MatchStatus{Is Match Finished?}
    
    %% Condition Check 2
    MatchStatus -- If Completed --> SaveResult[Update match_results]
    MatchStatus -- If Cancelled --> LogEntry[Update activity_log]

    %% Final Outcome
    SaveResult --> CalcPerf[Update player_performance]
    CalcPerf --> GenCert[Update certificates table]
    GenCert --> Activity[Record activity_log user_id]
```

---

## 📂 Database Table Analysis (PK/FK Mapping)

| Table Name | Primary Key (PK) | Foreign Keys (FK) | Logical Condition (If/Else) |
| :--- | :--- | :--- | :--- |
| **users** | `id` | None | `IF status='active' AND role='admin'` → Grant full access. |
| **players** | `id` | None | `IF status='active'` → Allow match participation. |
| **teams** | `id` | `sport_id` | `IF matches_won > 0` → Update leaderboard logic. |
| **matches** | `id` | `sport_id`, `team1_id`, `team2_id` | `IF team1_id == team2_id` → **ERROR** (Validation check). |
| **match_results** | `id` | `match_id`, `winner_team_id` | `IF winner_team_id IS NOT NULL` → Update team stats. |
| **certificates** | `id` | `player_id`, `sport_id`, `generated_by` | `IF issued` → Lock record from deletion. |

---

## 🔍 Deep Analysis Summary
1.  **Validation Logic**: The `matches` table has a recursive FK relationship where `team1_id` and `team2_id` must point to the `teams` table, but must be different (Condition: `IF id1 != id2`).
2.  **Audit Trail**: Every action (Diamond nodes in the flow) triggers an `INSERT` into the `activity_log` using the `users.id` as a Foreign Key.
3.  **Performance Scalability**: The `player_performance` table uses a composite-like lookup (FK match_id + FK player_id) to ensure one entry per player per match.
