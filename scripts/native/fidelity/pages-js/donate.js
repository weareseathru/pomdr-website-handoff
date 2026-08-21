// Scroll effect
  window.addEventListener('scroll', () => {
    document.getElementById('site-nav').classList.toggle('scrolled', window.scrollY > 40);
  });
// Amount selector
const amountBtns = document.querySelectorAll('.amount-btn');
const customInput = document.getElementById('custom-amount');
const donateBtn = document.getElementById('donate-btn');
let selectedAmount = 125;

amountBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    amountBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    selectedAmount = parseInt(btn.dataset.amount);
    customInput.value = '';
  });
});
customInput.addEventListener('input', () => {
  amountBtns.forEach(b => b.classList.remove('active'));
  selectedAmount = parseInt(customInput.value) || 0;
});
donateBtn.addEventListener('click', () => {
  const amt = customInput.value ? parseInt(customInput.value) : selectedAmount;
  if (amt > 0) {
    window.location.href = `/donation/?initialdonation=${amt}`;
  }
});
