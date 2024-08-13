import json

# Load intents from JSON file
with open('intents.json', 'r') as file:
    intents = json.load(file)

# Display intents
print("Intents:")
for intent in intents['intents']:
    print(intent)
