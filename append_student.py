import csv
from pathlib import Path

path = Path('data.csv')
row = [
    '1', '17', '5', '171', '1', '1', '120', '1', '19', '12', '5', '9', '127.3', '1', '0', '0', '1', '1', '0', '20', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '10.8', '1.4', '1.74', 'Graduate'
]
with path.open(newline='', mode='r', encoding='utf-8') as f:
    header = next(csv.reader(f))
    if len(header) != len(row):
        raise SystemExit(f'header len {len(header)} != row len {len(row)}')
with path.open(newline='', mode='a', encoding='utf-8') as f:
    writer = csv.writer(f)
    writer.writerow(row)
print(sum(1 for _ in path.open(encoding='utf-8')))
