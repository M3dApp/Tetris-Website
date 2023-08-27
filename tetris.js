const gridSize = { X: 10, Y: 20 };
const shapes = [
	["L", [ [1, 1], [1, 2], [1, 3], [2, 3] ]],
	["Z", [ [1, 1], [2, 1], [2, 2], [2, 3] ]],
	["S", [ [1, 2], [2, 1], [2, 2], [3, 1] ]],
	["T", [ [1, 1], [2, 1], [2, 2], [3, 1] ]],
	["O", [ [1, 1], [1, 2], [2, 1], [2, 2] ]],
	["I", [ [1, 1], [1, 2], [1, 3], [1, 4] ]]
];
const colors = [
	"lightblue",
	"darkblue",
	"orange",
	"yellow",
	"green",
	"magenta",
	"red"
];

var uiBlocks;
var currentBlock;
var color;
var timerId;
var offset = { X: 0, Y: 0 };
var score = 0;
var grid = new Array(gridSize.X);
for (let i = 0; i < gridSize.X; i++) {
	let arr = new Array(gridSize.Y);
	arr.fill("");
	grid[i] = arr;
}

function post(path, data) {
	const f = document.createElement("form");
	f.display = "none";
	f.method = "POST";
	f.action = path;
	for (const key in data) {
		if (data.hasOwnProperty(key)) {
			const field = document.createElement("input");
			field.type = "hidden";
			field.name = key;
			field.value = data[key];
			f.appendChild(field);
		}
	}
	document.body.appendChild(f);
	f.submit();
}

function startGame() {
	if (timerId) {
		clearInterval(timerId);
		timerId = null;
	} else {
		uiBlocks = Array.from(document.getElementsByClassName("block"));
		score = 0;
		offset.X = 0;
		offset.Y = 0;
		for (let x = 0; x < grid.length; x++) {
			for (let y = 0; y < grid[x].length; y++) {
				grid[x][y] = "";
			}
		}
		let butt = document.getElementById("start-button");
		butt.style.display = "none";
		let scoreLab = document.getElementById("score");
		scoreLab.innerHTML = "Score: " + score;
		scoreLab.style.display = "block";
		newPiece();
		timerId = setInterval( function() { movePiece(true, 0, 1); }, 1000);
	}
}

function gameOver() {
	clearInterval(timerId);
	timerId = null;
	post("leaderboard.php", { score: score });
}

function drawPiece() {
	let prevBlocks = Array.from(document.getElementsByClassName("block preview"));
	for (let i = 0; i < prevBlocks.length; i++) {
		prevBlocks[i].style.transform = "translate(" + (offset.X * 30) + "px, " + (offset.Y * 30) + "px)";
	}
}

function movePiece(place, addX, addY) {
	let empty = true;
	for (let i = 0; i < currentBlock[1].length; i++) {
		let X = currentBlock[1][i][0] + 3 + offset.X + addX;
		let Y = currentBlock[1][i][1] + offset.Y - 1 + addY;
		if (X < 0 || X >= gridSize.X) {
			empty = false;
			break;
		}
		if (Y >= gridSize.Y || grid[X][Y] != "") {
			empty = false;
			if (place) {
				placePiece();
				newPiece();
			}
			break;
		}
	}
	if (empty) {
		offset.X += addX;
		offset.Y += addY;		
	}
	drawPiece();
}

function placePiece() {
	for (let i = 0; i < currentBlock[1].length; i++) {
		let X = currentBlock[1][i][0] + 3 + offset.X;
		let Y = currentBlock[1][i][1] + offset.Y - 1;
		let num = X + (Y * gridSize.X);
		grid[X][Y] = currentBlock[0];
		uiBlocks[num].style.backgroundColor = color;
		uiBlocks[num].style.transform = null;
		uiBlocks[num].classList.remove("preview");
	}
	let loop = true;
	while (loop) {
		loop = false;
		for (let y = (gridSize.Y-1); y > 0; y--) {
			let full = true;
			for (let x = 0; x < gridSize.X; x++) {
				if (grid[x][y] == "") {
					full = false;
					break;
				}
			}
			if (full) {
				for (let x = 0; x < gridSize.X; x++) {
					let num = x + (y * gridSize.X);
					grid[x][y] = ""
					uiBlocks[num].style.backgroundColor = null;
					for (let z = y; z > 0; z--) {
						let num = x + (z * gridSize.X);
						grid[x][z] = grid[x][z-1];
						if (!uiBlocks[num-gridSize.X].classList.contains("preview")) {
							uiBlocks[num].style.backgroundColor = uiBlocks[num-gridSize.X].style.backgroundColor;
						} else {
							uiBlocks[num].style.backgroundColor = null;
						}
					}
				}
				loop = true;
				break;
			}
		}
	}
	score += 1;
	document.getElementById("score").innerHTML = "Score: " + score;
}

function newPiece() {
	let endGame = false;
	offset.X = 0;
	offset.Y = 0;
	let prevBlocks = Array.from(document.getElementsByClassName("block preview"));
	for (let i = 0; i < prevBlocks.length; i++) {
		prevBlocks[i].classList.remove("preview");
		prevBlocks[i].style.transform = null;
		prevBlocks[i].style.backgroundColor = null;
	}
	currentBlock = shapes[Math.floor(Math.random() * shapes.length)];
	color = colors[Math.floor(Math.random() * colors.length)];
	for (let i = 0; i < currentBlock[1].length; i++) {
		let X = currentBlock[1][i][0] + 3;
		let Y = currentBlock[1][i][1] - 1;
		let num = X + (Y * gridSize.X);
		if (grid[X][Y] == "") {
			uiBlocks[num].classList.add("preview");
			uiBlocks[num].style.transform = null;
			uiBlocks[num].style.backgroundColor = color;
		} else {
			endGame = true;
		}
	}
	if (endGame) {
		gameOver();
	}
}

document.addEventListener("keydown", function (e) {
	if (timerId) {
		if (e.keyCode === 39)  {
			movePiece(false, 1, 0); // Right
		}
		if (e.keyCode === 37) {
			movePiece(false, -1, 0); // Left
		}
		if (e.keyCode == 40) {
			movePiece(true, 0, 1); // Down
		}
	}
});