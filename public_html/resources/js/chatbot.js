document.addEventListener('DOMContentLoaded', function() {
    fetch('http://localhost:3000/chatbot', {
        credentials: 'include'
    })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            data.history.forEach(message => {
                addMessage(message.content, message.role === "user" ? "Vous" : "Gilou");
            });
        })
        .catch(error => {
            addMessage("Une erreur est survenue lors du chargement de l'historique", "Gilou")
        });
});

function openChatbot() {
    document.getElementById("chatbot").style.display = "block";
    document.getElementById("chatbot-button").style.display = "none";
    const messages = document.getElementById("chatbot-chat");
    messages.scrollTop = messages.scrollHeight;
}

function closeChatbot() {
    document.getElementById("chatbot").style.display = "none";
    document.getElementById("chatbot-button").style.display = "block";
}

function sendMessage() {
    const message = document.getElementById("chatbot-input").value;
    if (message !== "") {
        document.getElementById("chatbot-input").value = "";
        addMessage(message, "Vous")
        showLoading();
        fetch("http://localhost:3000/chatbot", {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ message })
        })
            .then(response => {
                if (!response.ok)
                    throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                addMessage(data.answer, "Gilou");
            })
            .catch(error => addMessage("Oups on dirait qu'il y a eu un problème. Renvois ton message plustard.", "Gilou"));

    }
}

function addMessage(message, authorName) {
    const messages = document.getElementById("chatbot-chat");
    const messageElement = document.createElement("div");
    messageElement.className = "chatbot-message";
    if(authorName === "Vous") {
    messageElement.innerHTML = `<div class="flex w-full mt-2 space-x-3 max-w-xs ml-auto justify-end">
				<div class="flex flex-col items-end gap-2">
					<div class="bg-blue-600 text-white p-3 rounded-l-lg rounded-br-lg">
						<p class="text-sm">${message}</p>
					</div>
					<span class="text-xs text-zinc-500 leading-none text-right">${authorName}</span>
				</div>
			</div>`;
    } else {
        const loading = document.getElementById("chatbot-loading");
        if(loading) loading.remove();
        messageElement.innerHTML = `<div class="flex w-full mt-2 space-x-3 max-w-xs">
        <div class="flex flex-col gap-2">
            <div class="bg-zinc-200 p-3 rounded-r-lg rounded-bl-lg">
                <p class="text-sm">${message}</p>
            </div>
            <span class="text-xs text-zinc-300 leading-none">${authorName}</span>
        </div>
    </div>`;}
    messages.appendChild(messageElement);
    messages.scrollTop = messages.scrollHeight;
}

function showLoading() {
    const messages = document.getElementById("chatbot-chat");
    messages.innerHTML+= `<div class="flex w-full mt-2 space-x-3 max-w-xs" id="chatbot-loading">
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-1 bg-zinc-200 p-3 rounded-r-lg rounded-bl-lg">
                                <div class='h-2 w-2 bg-zinc-700 rounded-full animate-bounce [animation-delay:-0.3s]'></div>
                                <div class='h-2 w-2 bg-zinc-700 rounded-full animate-bounce [animation-delay:-0.15s]'></div>
                                <div class='h-2 w-2 bg-zinc-700 rounded-full animate-bounce'></div>
                            </div>
                            <span class="text-xs text-zinc-300 leading-none">Gilou</span>
                        </div>
                    </div>`
    messages.scrollTop = messages.scrollHeight;
}

document.getElementById("chatbot-input").addEventListener("keyup", function(event) {
    if (event.key === "Enter") {
        sendMessage();
    }
});