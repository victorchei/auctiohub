# ER Diagram — AuctioHub

Mermaid діаграма доменної моделі (7 сутностей + audit_logs).

```mermaid
erDiagram
    USER ||--o{ LOT : "sells (seller_id)"
    USER ||--o{ LOT : "won (winner_id, nullable)"
    USER ||--o{ BID : places
    USER ||--o{ COMMENT : writes
    USER ||--o{ REVIEW : "as buyer"
    USER ||--o{ REVIEW : "as seller"
    USER }o--o{ LOT : watches

    CATEGORY ||--o{ LOT : contains
    CATEGORY ||--o{ CATEGORY : "parent (nested)"

    LOT ||--o{ LOT_IMAGE : has
    LOT ||--o{ BID : receives
    LOT ||--o{ COMMENT : has
    LOT ||--|| REVIEW : "after-sale"

    COMMENT ||--o{ COMMENT : "parent (threads)"

    USER {
        bigint id PK
        string name
        string email UK
        string password
        enum role "user|admin"
        string avatar_path NULL
        timestamp banned_at NULL
        timestamp email_verified_at NULL
    }
    CATEGORY {
        bigint id PK
        string name
        string slug UK
        bigint parent_id FK NULL
        text description NULL
    }
    LOT {
        bigint id PK
        bigint seller_id FK
        bigint category_id FK
        bigint winner_id FK NULL
        string title
        string slug UK
        text description
        decimal starting_price
        decimal current_price
        decimal bid_increment
        timestamp starts_at
        timestamp ends_at
        enum status "draft|active|ended|cancelled"
        string cover_image_path NULL
        timestamp deleted_at NULL "softDeletes"
    }
    LOT_IMAGE {
        bigint id PK
        bigint lot_id FK
        string path
        smallint sort_order
    }
    BID {
        bigint id PK
        bigint lot_id FK
        bigint user_id FK
        decimal amount
        timestamp placed_at
        unique "(lot_id, amount)"
    }
    COMMENT {
        bigint id PK
        bigint lot_id FK
        bigint user_id FK
        bigint parent_id FK NULL "self-ref for threads"
        text body
        timestamp deleted_at NULL "softDeletes"
    }
    REVIEW {
        bigint id PK
        bigint lot_id FK UK "one per lot"
        bigint buyer_id FK
        bigint seller_id FK
        tinyint rating "1-5"
        text body NULL
    }
    AUDIT_LOG {
        bigint id PK
        bigint user_id FK NULL
        string action "created|updated|deleted|restored"
        string subject_type
        bigint subject_id
        json payload NULL
    }
```

## Constraints

- `lots.seller_id` → cascade on delete (продавець видалений → лоти теж)
- `lots.category_id` → **restrict** on delete (не можна видалити категорію з лотами)
- `lots.winner_id` → null on delete (переможець видалений → лот зберігається без winner)
- `bids.(lot_id, amount)` — UNIQUE (idempotency)
- `watchlist.(user_id, lot_id)` — composite primary key

## Індекси

- `lots(status, ends_at)` — для пошуку active/ending-soon
- `lots(category_id)` — для фільтру по категорії
- `bids(lot_id, placed_at)` — для історії ставок
- `bids(user_id, placed_at)` — для профілю користувача
- `comments(lot_id, parent_id)` — для деревовидної структури
- `reviews(seller_id)` — для рейтингу продавця
- `audit_logs(subject_type, subject_id)` — для пошуку дій по об'єкту
- `audit_logs(user_id, created_at)` — для пошуку дій користувача

## Soft Deletes

Тільки на `lots` і `comments`. Інші сутності — hard delete з cascade.
