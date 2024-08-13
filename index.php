<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Chat Bot</title>
      <style>
            #chat-container {
                  width: 300px;
                  margin: 20px auto;
                  border: 1px solid #ccc;
                  padding: 10px;
                  border-radius: 5px;
                  background-color: #f9f9f9;
                  overflow-y: scroll;
                  max-height: 300px;
            }

            #input-container {
                  margin-top: 10px;
                  width: 200px;
                  margin: auto;
            }
      </style>
</head>

<body>

      <div id="chat-container"></div>

      <div id="input-container">
            <input type="text" id="user-input" placeholder="Type your message...">
      </div>

      <script>

            document.getElementById("user-input").addEventListener("keypress", function (e) {
                  if (e.keyCode === 13) {
                        sendMessage();
                  }
            });

            const chatContainer = document.getElementById('chat-container');
            const userInput = document.getElementById('user-input');

            let intents = [];

            // Load intents from response.json
            fetch('response.json').then(response => response.json()).then(data => {
                  intents = data.intents;
            }).catch(error => console.error('Error loading intents:', error));

            function sendMessage() {
                  const message = userInput.value;
                  if (message.trim() === '') return;

                  const userMessage = document.createElement('div');
                  userMessage.textContent = `You: ${message}`;
                  chatContainer.appendChild(userMessage);

                  // Find matching intent and generate bot response
                  const botResponse = generateBotResponse(message);
                  displayBotMessage(botResponse);

                  userInput.value = '';
            }

            function generateBotResponse(message) {
                  const threshold = 0.5; // Adjust threshold as needed

                  let bestMatch = { similarity: 0, response: "I'm sorry, I don't understand." };

                  for (const intent of intents) {
                        for (const pattern of intent.patterns) {
                              const similarity = calculateSimilarity(message, pattern);
                              if (similarity > threshold && similarity > bestMatch.similarity) {
                                    bestMatch = { similarity, response: getRandomResponse(intent.responses) };
                              }
                        }
                  }

                  return bestMatch.response;
            }

            function calculateSimilarity(message, pattern) {
                  const messageWords = message.toLowerCase().split(" ");
                  const patternWords = pattern.toLowerCase().split(" ");
                  const commonWords = messageWords.filter(word => patternWords.includes(word));

                  return commonWords.length / patternWords.length;
            }

            function getRandomResponse(responses) {
                  return responses[Math.floor(Math.random() * responses.length)];
            }

            function displayBotMessage(message) {
                  const botMessage = document.createElement('div');
                  botMessage.textContent = `Bot: ${message}`;
                  chatContainer.appendChild(botMessage);
                  chatContainer.scrollTop = chatContainer.scrollHeight;
            }
      </script>

</body>

</html>