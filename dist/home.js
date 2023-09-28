// Generate random bubbles
const bubbleContainer = document.querySelector(".bubble-container");

function createRandomBubble() {
  const bubble = document.createElement("div");
  bubble.className = "bubble";

  const randomSize = Math.floor(Math.random() * 20) + 5;
  const randomX = Math.random() * 100;
  const randomDelay = Math.random() * 5;

  bubble.style.width = `${randomSize}px`;
  bubble.style.height = `${randomSize}px`;
  bubble.style.left = `${randomX}%`;
  bubble.style.animationDelay = `${randomDelay}s`;

  bubbleContainer.appendChild(bubble);
}

// Create bubbles on page load
for (let i = 0; i < 20; i++) {
  createRandomBubble();
}
