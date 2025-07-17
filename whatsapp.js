document.addEventListener("DOMContentLoaded", function () {
  const whatsappBtn = document.createElement("div");
  whatsappBtn.id = "whatsapp-float";
  whatsappBtn.innerHTML = `
    <a href="https://wa.me/254705556565?text=Hi%20Dayroh,%20I%20need%20help" target="_blank" title="Chat with us on WhatsApp">
      <img src="https://img.icons8.com/color/48/000000/whatsapp--v1.png" alt="WhatsApp">
    </a>
  `;
  document.body.appendChild(whatsappBtn);
});
