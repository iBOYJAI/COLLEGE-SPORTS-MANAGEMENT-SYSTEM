# Entity Relationship (ER) Diagram - College Sports Management System

This document provides a clean and detailed ER diagram for the system database.

---

## 📊 Entity Relationship Diagram

```mermaid
erDiagram
    USERS ||--o{ ACTIVITY_LOG : "performs"
    USERS ||--o{ CERTIFICATES : "generates"
    
    PLAYERS ||--o{ PLAYER_SPORTS : "registers_for"
    SPORTS_CATEGORIES ||--o{ PLAYER_SPORTS : "includes"
    
    SPORTS_CATEGORIES ||--o{ TEAMS : "has"
    TEAMS ||--o{ TEAM_PLAYERS : "consists_of"
    PLAYERS ||--o{ TEAM_PLAYERS : "belongs_to"
    
    SPORTS_CATEGORIES ||--o{ MATCHES : "records"
    TEAMS ||--o{ MATCHES : "competes_in"
    
    MATCHES ||--o| MATCH_RESULTS : "finalized_as"
    TEAMS ||--o{ MATCH_RESULTS : "wins"
    
    MATCHES ||--o{ PLAYER_PERFORMANCE : "tracks"
    PLAYERS ||--o{ PLAYER_PERFORMANCE : "achieves"
    
    PLAYERS ||--o{ CERTIFICATES : "receives"
    SPORTS_CATEGORIES ||--o{ CERTIFICATES : "awarded_in"

    USERS {
        int id PK
        string full_name
        string username
        string email
        string password
        string role
        string status
    }

    PLAYERS {
        int id PK
        string name
        string register_number UK
        date dob
        string department
        string mobile
        string status
    }

    SPORTS_CATEGORIES {
        int id PK
        string sport_name UK
        string category_type
        int min_players
        int max_players
    }

    TEAMS {
        int id PK
        string team_name
        int sport_id FK
        string coach_name
    }

    MATCHES {
        int id PK
        int sport_id FK
        int team1_id FK
        int team2_id FK
        date match_date
        time match_time
        string venue
        string status
    }

    MATCH_RESULTS {
        int id PK
        int match_id FK
        int team1_score
        int team2_score
        int winner_team_id FK
    }

    PLAYER_PERFORMANCE {
        int id PK
        int match_id FK
        int player_id FK
        int runs_scored
        int goals
        int points
    }

    CERTIFICATES {
        int id PK
        int player_id FK
        int sport_id FK
        string certificate_type
        date issue_date
        int generated_by FK
    }

    ACTIVITY_LOG {
        int id PK
        int user_id FK
        string action_type
        string module
        timestamp created_at
    }
```

---

## 🔑 Key Relationships

| Relationship | Type | Description |
| :--- | :--- | :--- |
| **Players & Team** | Many-to-Many | A player can be in multiple teams, and teams have many players (via `team_players`). |
| **Teams & Sports** | Many-to-One | Multiple teams can exist for a single sport category. |
| **Matches & Teams** | One-to-Many | Each match involves two teams (`team1_id`, `team2_id`). |
| **Certificates** | One-to-Many | A player can earn multiple certificates managed by admins. |

---
