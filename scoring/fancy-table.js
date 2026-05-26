(function(){
	for (const body of document.querySelectorAll(".fancy_table > tbody")) {
		let header;

		const baseRep = [];

		{
			let stage = 0;

			for (const child of body.children) {
				switch (stage) {
					case 0:
						if (child.firstChild.tagName == "TD") {
							stage++;
							break;
						}

						header = child;
						break;

					case 1:
						baseRep.push(child);
						break;
				}
			}
		};

		let currentRep = [];

		function copyBase() {
			return baseRep.map(r => r.cloneNode());
		}

		function applyRep(rep) {
			for (const row of currentRep) {
				row.remove();
			}

			for (const row of rep) {
				body.appendChild(row);
			}
		}

		function typeJumble(x) {
			return Number(x) || (Date(x) === "Invalid Date" ? null : x) || x;
		}

		function applySorting(rep) {
			let heading;
			let i;

			for (heading of header) {
				if (heading.sortState)
					break;

				i++;
			}

			rep.sort((a, b) => typeJumble(a[i]) > typeJumble(b[i]) ? sortState : -sortState);
		}

		for (const heading of header) {
			const sortBtn = document.createElement("button");

			heading.sortState = 0;

			heading.updateSortState = (newState = null) => {
				if (newState) {
					heading.sortState = newState;
				} else {
					heading.sortState = [0, 1, -1][sortBtn.sortState + 1]
				}

				sortBtn.innerText = ["↓", "·", "↑"][sortBtn.sortState + 1];
			};

			heading.updateSortState(0);

			sortBtn.addEventListener("click", () => {
				for (const otherHeading of header) {
					if (heading == otherHeading)
						continue;

					otherHeading.updateSortState(0);
				}

				const data = copyBase();

				heading.updateSortState();

				applySorting(data);

				applyRep(data);
			});

			heading.appendChild(sortBtn);
		}
})();
