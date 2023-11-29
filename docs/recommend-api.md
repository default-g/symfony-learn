# Recommendation API

GET /api/v1/book/{book_id}/recommendations

Authorization: Bearer {token}

```json
{
  "id": 1,
  "ts": 112112,
  "recommendations": [
    {"id": 122}
  ]
}
```

## 403
```json
{
  "error": "Access denied"
}
```
