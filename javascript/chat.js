var lastSuggestedPrice = null;

        function suggestSellPrice() {
            var sellPrice = document.getElementById("sell_price").value;

            if (sellPrice.trim() === "" || isNaN(sellPrice)) {
                console.error("Please enter a valid sell price.");
                return;
            }

            var content = "Sell price suggestion: $" + sellPrice;

            lastSuggestedPrice = sellPrice;

            sendMessage(content);

            var chatId = document.getElementById("chat_id").value;
            var itemId = document.getElementById("item_id").value;

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log('Last suggested price updated successfully.');
                    } else {
                        console.error('Error updating last suggested price:', xhr.status);
                    }
                }
            };
            xhr.open("POST", "../actions/action_update_last_suggested_price.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("chat_id=" + encodeURIComponent(chatId) + "&item_id=" + encodeURIComponent(itemId) + "&last_suggested_price=" + encodeURIComponent(lastSuggestedPrice));
        }


        function addToCart() {
            if (lastSuggestedPrice === null) {
                console.error("No suggested price available");
                return;
            }

            var chatId = document.getElementById("chat_id_add").value; 
            document.getElementById("add-to-cart-form").submit();
        }

        function sendMessage(content) {
            if (content.trim() === "") {
                console.log("Message is empty, not sending.");
                return; 
            }
            var chatId = document.getElementById("chat_id").value;
            var senderId = document.getElementById("sender_id").value;
            var receiverId = document.getElementById("receiver_id").value;
            var itemId = document.getElementById("item_id").value; 

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById("content").value = "";  
                        fetchMessages();  
                    } else {
                        console.error('Error sending message:', xhr.status);
                    }
                }
            };
            xhr.open("POST", "../actions/action_send_message.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("chat_id=" + encodeURIComponent(chatId) + "&sender_id=" + encodeURIComponent(senderId) + "&receiver_id=" + encodeURIComponent(receiverId) + "&item_id=" + encodeURIComponent(itemId) + "&content=" + encodeURIComponent(content));
        }

        function fetchMessages() {
            var chatId = document.getElementById("chat_id").value;
            var itemId = document.getElementById("item_id").value;

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        try {
                            var messages = JSON.parse(xhr.responseText);
                            displayMessages(messages);
                        } catch (e) {
                            console.error('Error parsing JSON response:', e);
                            console.error('Response text:', xhr.responseText);
                        }
                    } else {
                        console.error('Error fetching messages:', xhr.status, xhr.statusText);
                        console.error('Response text:', xhr.responseText);
                    }
                }
            };
            xhr.open("GET", "../actions/action_fetch_message.php?chat_id=" + encodeURIComponent(chatId) + "&item_id=" + encodeURIComponent(itemId), true);
            xhr.send();
        }

        function fetchPrices() {
        var chatId = document.getElementById("chat_id").value;
        var itemId = document.getElementById("item_id").value;

        var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.lastSuggestedPrice !== lastSuggestedPrice) {
                                lastSuggestedPrice = response.lastSuggestedPrice;
                                if (lastSuggestedPrice !== null && lastSuggestedPrice !== "") {
                                    document.getElementById("add-to-cart-submit").textContent = "Add to Cart $" + lastSuggestedPrice;
                                    document.getElementById("last_suggested_price").value = lastSuggestedPrice;
                                    document.getElementById("add-to-cart-container").style.display = "block";
                                }
                            }
                        } catch (e) {
                            console.error('Error parsing price response:', e);
                            console.error('Response text:', xhr.responseText);
                        }
                    } else {
                        console.error('Error fetching last suggested price:', xhr.status, xhr.statusText);
                        console.error('Response text:', xhr.responseText);
                    }
                }
            };
            xhr.open("GET", "../actions/action_suggested_prices.php?chat_id=" + encodeURIComponent(chatId) + "&item_id=" + encodeURIComponent(itemId), true);
            xhr.send();
        }

        function displayMessages(messages) {
            var chatMessagesDiv = document.getElementById("chat-messages");
            chatMessagesDiv.innerHTML = "";
            var senderId = document.getElementById("sender_id").value;

            messages.forEach(function(message) {
                var messageDiv = document.createElement("div");
                messageDiv.classList.add("message");
                if (message.senderId == senderId) {
                    messageDiv.classList.add("sender");
                } else {
                    messageDiv.classList.add("receiver");
                }
                messageDiv.innerHTML = `
                    <div class="sender-name">${message.senderUsername}</div>
                    <div class="timestamp">${message.timestamp}</div>
                    <div class="content">${message.content}</div>
                `;
                chatMessagesDiv.appendChild(messageDiv);
            });
        }

        document.getElementById("message-form").addEventListener("submit", function(event) {
            event.preventDefault(); 
            sendMessage(document.getElementById("content").value); 
        });
        
        fetchPrices();
        fetchMessages(); 
        setInterval(fetchPrices, 5000); 
        setInterval(fetchMessages, 5000); 
