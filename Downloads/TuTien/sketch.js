let gameState = 'start';
let thamGiaButton;

function setup() {
  let canvas = createCanvas(800, 600);
  canvas.parent('start-screen');
  console.log('Setup hoàn tất: Canvas được tạo với kích thước 800x600.');
  
  // Tạo nút Tham gia
  thamGiaButton = createButton('Tham gia');
  thamGiaButton.position(width / 2 - 50, height / 2);
  thamGiaButton.mousePressed(startGame);
  thamGiaButton.style('font-size', '24px');
  thamGiaButton.style('padding', '10px 20px');
  thamGiaButton.style('background-color', '#ffffff');
  thamGiaButton.style('color', '#000000');
  console.log('Nút Tham gia được tạo tại vị trí trung tâm.');
}

function draw() {
  if (gameState === 'start') {
    background(0); // Nền màu đen
    console.log('Vẽ khung hình: gameState = start');
    textSize(32);
    textAlign(CENTER);
    fill(255); // Chữ màu trắng
    text('Trò Chơi Tu Tiên', width / 2, height / 2 - 50);
    textSize(20);
    fill(255);
    text('Nhấn "Tham gia" để bắt đầu', width / 2, height / 2 + 100);
  }
}

function startGame() {
  gameState = 'playing';
  thamGiaButton.hide();
  document.getElementById('start-screen').style.display = 'none';
  document.getElementById('game-screen').style.display = 'block';
  console.log('Trò chơi bắt đầu: gameState = playing');
}