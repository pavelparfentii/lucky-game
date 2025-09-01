#Test Task - Lucky Game

Тестове завдання з реалізацією гри "Imfeelinglucky" з унікальними посиланнями та історією результатів.

## Features

- **Home page**: Registration form with username and phone_number fields
- **Unique links**: after registration user gets unique link available for 7 days
- **Game "Imfeelinglucky"**: random number generation with winnings calculation
- **History**: Game last 3 results
- **links management**: ability to create new and deactivate links

## Tech reqs

- **Database**: SQLite
- **Cache**: file
- **Queues**: sync 
- **Docker**: true

## Installation

### 1: Clone project

Clone project from the repo, cd to_test_proj

### 2: Run Docker

Simply run:
```bash
docker-compose up -d
```

*If needed:*
```bash
sudo docker-compose up -d
```

### 3: Open browser
Go to [http://localhost:8084](http://localhost:8084)

## Game rules


1. **Random number**: generated from 1 to 1000
2. **Result**: Win if the number is even, Lose if odd
3. **Winning amount** (only for Win):
- \> 900: 70% of the number
- \> 600: 50% of the number 
- \> 300: 30% of the number
- ≤ 300: 10% of the number

## Development

### Install dependencies
```bash
docker-compose exec app composer install
```
