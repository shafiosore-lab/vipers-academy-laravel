# Tournament System API Documentation

## Overview

This document provides comprehensive API documentation for the tournament management system, including all governance endpoints and their usage.

## Base URL

```
https://your-domain.com/api
```

## Authentication

All API endpoints require authentication using Laravel Sanctum tokens.

### Authentication Headers

```
Authorization: Bearer {your-api-token}
Accept: application/json
Content-Type: application/json
```

## Rate Limiting

API requests are rate-limited to prevent abuse:
- **General endpoints**: 60 requests per minute
- **Write operations**: 30 requests per minute

## Response Format

All successful responses follow this format:

```json
{
    "success": true,
    "data": { /* response data */ },
    "message": "Success message"
}
```

Error responses follow this format:

```json
{
    "message": "Error message",
    "errors": {
        "field_name": ["Error message for field"]
    }
}
```

## Governance API Endpoints

### Age Verification Endpoints

#### Get Player Age Verification Status

**GET** `/governance/age-verification/players/{player}/status`

Returns the age verification status for a specific player.

**Parameters:**
- `player` (integer, required): Player ID

**Response:**
```json
{
    "is_verified": true,
    "verification_date": "2023-12-01",
    "needs_verification": false,
    "verification_flagged_at": null,
    "verification_flagged_by": null,
    "age_alerts": [],
    "verification_history": []
}
```

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/governance/age-verification/players/123/status" \
  -H "Authorization: Bearer your-api-token" \
  -H "Accept: application/json"
```

#### Get Active Age Verification Rules

**GET** `/governance/age-verification/rules/{organization}/active`

Returns all active age verification rules for an organization.

**Parameters:**
- `organization` (integer, required): Organization ID

**Response:**
```json
[
    {
        "id": 1,
        "name": "U18 Age Verification",
        "category": "youth",
        "min_age": 16,
        "max_age": 18,
        "alert_threshold_days": 30,
        "is_active": true,
        "auto_flag": true,
        "notes": "Verify players under 18 before tournament registration"
    }
]
```

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/governance/age-verification/rules/1/active" \
  -H "Authorization: Bearer your-api-token" \
  -H "Accept: application/json"
```

#### Verify Player Age

**POST** `/governance/age-verification/players/{player}/verify`

Records age verification for a player.

**Parameters:**
- `player` (integer, required): Player ID

**Request Body:**
```json
{
    "verification_date": "2023-12-01",
    "verified_by": "Admin User",
    "verification_method": "Document Review",
    "notes": "Age verified successfully",
    "is_verified": true
}
```

**Validation Rules:**
- `verification_date`: required, date
- `verified_by`: required, string (max 255)
- `verification_method`: required, string (max 100)
- `notes`: optional, string (max 1000)
- `is_verified`: required, boolean

**Response:**
```json
{
    "success": true,
    "message": "Player verification recorded successfully.",
    "player": {
        "id": 123,
        "name": "Player Name",
        "is_age_verified": true,
        "age_verification_date": "2023-12-01"
    }
}
```

**Example Request:**
```bash
curl -X POST "https://your-domain.com/api/governance/age-verification/players/123/verify" \
  -H "Authorization: Bearer your-api-token" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "verification_date": "2023-12-01",
    "verified_by": "Admin User",
    "verification_method": "Document Review",
    "notes": "Age verified successfully",
    "is_verified": true
  }'
```

### Disciplinary Case Endpoints

#### Get Case Status

**GET** `/governance/disciplinary/cases/{case}/status`

Returns the current status and details of a disciplinary case.

**Parameters:**
- `case` (integer, required): Disciplinary case ID

**Response:**
```json
{
    "id": 1,
    "case_number": "DC-2023-001",
    "status": "open",
    "decision": null,
    "decision_date": null,
    "review_started_at": null,
    "review_started_by": null,
    "has_suspension": false,
    "suspension_details": null,
    "has_appeal": false,
    "appeal_details": null
}
```

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/governance/disciplinary/cases/1/status" \
  -H "Authorization: Bearer your-api-token" \
  -H "Accept: application/json"
```

#### Get Player Disciplinary History

**GET** `/governance/disciplinary/players/{player}/history`

Returns all disciplinary cases for a specific player.

**Parameters:**
- `player` (integer, required): Player ID

**Response:**
```json
[
    {
        "id": 1,
        "case_number": "DC-2023-001",
        "incident_type": "Red Card",
        "description": "Violent conduct during match",
        "incident_date": "2023-11-15",
        "status": "closed",
        "decision": "Suspended for 3 matches",
        "suspension": {
            "id": 1,
            "start_date": "2023-11-20",
            "end_date": "2023-12-10",
            "matches": 3,
            "reason": "Violent conduct"
        },
        "appeal": null
    }
]
```

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/governance/disciplinary/players/123/history" \
  -H "Authorization: Bearer your-api-token" \
  -H "Accept: application/json"
```

#### Get Active Suspensions

**GET** `/governance/disciplinary/suspensions/active`

Returns all active player suspensions for the authenticated user's organization.

**Response:**
```json
[
    {
        "id": 1,
        "player_id": 123,
        "start_date": "2023-11-20",
        "end_date": "2023-12-10",
        "matches": 3,
        "reason": "Violent conduct",
        "disciplinary_case_id": 1,
        "player": {
            "id": 123,
            "name": "Player Name",
            "team_id": 456
        },
        "disciplinary_case": {
            "id": 1,
            "case_number": "DC-2023-001",
            "incident_type": "Red Card"
        }
    }
]
```

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/governance/disciplinary/suspensions/active" \
  -H "Authorization: Bearer your-api-token" \
  -H "Accept: application/json"
```

### Appeals Endpoints

#### Get Case Appeals

**GET** `/governance/appeals/cases/{case}/appeals`

Returns all appeals for a specific disciplinary case.

**Parameters:**
- `case` (integer, required): Disciplinary case ID

**Response:**
```json
[
    {
        "id": 1,
        "disciplinary_case_id": 1,
        "player_id": 123,
        "grounds": "Decision was too harsh",
        "evidence": [],
        "status": "pending",
        "review_started_at": null,
        "review_started_by": null,
        "outcome": null,
        "reviewer": null
    }
]
```

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/governance/appeals/cases/1/appeals" \
  -H "Authorization: Bearer your-api-token" \
  -H "Accept: application/json"
```

#### Get Player Appeals

**GET** `/governance/appeals/players/{player}/appeals`

Returns all appeals submitted by a specific player.

**Parameters:**
- `player` (integer, required): Player ID

**Response:**
```json
[
    {
        "id": 1,
        "disciplinary_case_id": 1,
        "player_id": 123,
        "grounds": "Decision was too harsh",
        "evidence": [],
        "status": "pending",
        "review_started_at": null,
        "review_started_by": null,
        "outcome": null,
        "reviewer": null,
        "disciplinary_case": {
            "id": 1,
            "case_number": "DC-2023-001",
            "incident_type": "Red Card"
        }
    }
]
```

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/governance/appeals/players/123/appeals" \
  -H "Authorization: Bearer your-api-token" \
  -H "Accept: application/json"
```

### Protests Endpoints

#### Get Match Protests

**GET** `/governance/protests/matches/{match}/protests`

Returns all protests filed for a specific match.

**Parameters:**
- `match` (integer, required): Match ID

**Response:**
```json
[
    {
        "id": 1,
        "match_id": 456,
        "team_id": 789,
        "protest_type": "Referee Decision",
        "description": "Incorrect offside call",
        "grounds": "Video evidence shows player was onside",
        "evidence": [],
        "status": "pending",
        "review_started_at": null,
        "review_started_by": null,
        "outcome": null,
        "team": {
            "id": 789,
            "name": "Team Name"
        }
    }
]
```

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/governance/protests/matches/456/protests" \
  -H "Authorization: Bearer your-api-token" \
  -H "Accept: application/json"
```

#### Get Team Protests

**GET** `/governance/protests/teams/{team}/protests`

Returns all protests filed by a specific team.

**Parameters:**
- `team` (integer, required): Team ID

**Response:**
```json
[
    {
        "id": 1,
        "match_id": 456,
        "team_id": 789,
        "protest_type": "Referee Decision",
        "description": "Incorrect offside call",
        "grounds": "Video evidence shows player was onside",
        "evidence": [],
        "status": "pending",
        "review_started_at": null,
        "review_started_by": null,
        "outcome": null,
        "match": {
            "id": 456,
            "home_team_id": 789,
            "away_team_id": 999,
            "kickoff_time": "2023-11-15 15:00:00"
        }
    }
]
```

**Example Request:**
```bash
curl -X GET "https://your-domain.com/api/governance/protests/teams/789/protests" \
  -H "Authorization: Bearer your-api-token" \
  -H "Accept: application/json"
```

## Error Codes

| HTTP Code | Error Type | Description |
|-----------|------------|-------------|
| 400 | Bad Request | Invalid request parameters |
| 401 | Unauthorized | Authentication required |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable Entity | Validation errors |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Server error |

## Permissions

### Required Roles

- **Age Verification Endpoints**: `admin` or `super-admin`
- **Disciplinary Endpoints**: `admin` or `super-admin`
- **Appeals Endpoints**: `admin` or `super-admin`
- **Protests Endpoints**: `admin` or `super-admin`

### Organization Isolation

All endpoints respect organization boundaries. Users can only access data for their own organization unless they have super-admin privileges.

## SDK Examples

### JavaScript (Fetch API)

```javascript
const API_BASE = 'https://your-domain.com/api';
const TOKEN = 'your-api-token';

async function getPlayerStatus(playerId) {
    const response = await fetch(`${API_BASE}/governance/age-verification/players/${playerId}/status`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${TOKEN}`,
            'Accept': 'application/json'
        }
    });
    
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    return response.json();
}

async function verifyPlayer(playerId, verificationData) {
    const response = await fetch(`${API_BASE}/governance/age-verification/players/${playerId}/verify`, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${TOKEN}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(verificationData)
    });
    
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    return response.json();
}
```

### Python (Requests)

```python
import requests

API_BASE = 'https://your-domain.com/api'
TOKEN = 'your-api-token'

def get_player_status(player_id):
    headers = {
        'Authorization': f'Bearer {TOKEN}',
        'Accept': 'application/json'
    }
    
    response = requests.get(f'{API_BASE}/governance/age-verification/players/{player_id}/status', headers=headers)
    response.raise_for_status()
    
    return response.json()

def verify_player(player_id, verification_data):
    headers = {
        'Authorization': f'Bearer {TOKEN}',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
    
    response = requests.post(f'{API_BASE}/governance/age-verification/players/{player_id}/verify', 
                           headers=headers, json=verification_data)
    response.raise_for_status()
    
    return response.json()
```

## Webhook Events

The system supports webhook events for real-time notifications. Configure webhooks in the admin panel.

### Available Events

- `age_verification.created` - New age verification record
- `age_verification.updated` - Age verification status changed
- `disciplinary_case.created` - New disciplinary case
- `disciplinary_case.updated` - Disciplinary case status changed
- `disciplinary_case.decision` - Decision made on case
- `appeal.created` - New appeal filed
- `appeal.decision` - Appeal decision made
- `protest.created` - New protest filed
- `protest.decision` - Protest decision made

### Webhook Payload

```json
{
    "event": "age_verification.created",
    "timestamp": "2023-12-01T10:30:00Z",
    "data": {
        "player_id": 123,
        "is_verified": true,
        "verification_date": "2023-12-01",
        "verified_by": "Admin User"
    }
}
```

## Testing

### Test Environment

Use the test environment for development:

```
https://test.your-domain.com/api
```

### Test Data

Test endpoints with sample data:

```bash
# Test age verification endpoint
curl -X GET "https://test.your-domain.com/api/governance/age-verification/players/1/status" \
  -H "Authorization: Bearer test-token" \
  -H "Accept: application/json"
```

## Support

For API support and questions:
- Documentation: [https://your-domain.com/docs/api](https://your-domain.com/docs/api)
- Support Email: api-support@your-domain.com
- Developer Forum: [https://forum.your-domain.com](https://forum.your-domain.com)
