const form = document.getElementById("input");
//add submit input event listener
form.addEventListener("submit", function (event) {
  // stop form submission and page refreshing
  event.preventDefault();

  //clear previous results
  document.getElementById("accordian").innerHTML = "";

  const startDate = form.elements["startDate"].value;
  let endDate;
  if (document.getElementById("date-range-checkbox").checked) {
    endDate = form.elements["endDate"].value;
  } else {
    endDate = startDate;
  }
  const partySize = form.elements["partySize"].value;
  const cateringGrade = form.elements["cateringGrade"].value;

  const dates = getDatesInRange(startDate, endDate);
  fetch(
    "server-query.php?dates=" +
      dates +
      "&partySize=" +
      partySize +
      "&cateringGrade=" +
      cateringGrade
  )
    .then((res) => res.json())
    .then(console.log)
    .then((data) => displayResults(data, partySize))
    .catch((error) => console.log(error));
});

function displayResults(data, partySize) {
  let i = 0;
  for (let [k, v] of Object.entries(data)) {
    const date = new Date(k);
    const map =
      "Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday".split(",");
    const dateStr =
      date.toISOString().split("T")[0].split("-")[2] +
      "/" +
      date.toISOString().split("T")[0].split("-")[1] +
      "/" +
      date.toISOString().split("T")[0].split("-")[0] +
      " " +
      map[date.getDay()];
    displayVenuesOnDate(dateStr, v, i, partySize);
    i++;
  }
}
//returns array of string dates between 2 input dates inclusive
function getDatesInRange(startDateStr, endDateStr) {
  const startDate = new Date(startDateStr);
  const endDate = new Date(endDateStr);
  const date = new Date(startDate);

  const dates = [];

  while (date <= endDate) {
    dates.push(date.toISOString().split("T")[0]);
    date.setDate(date.getDate() + 1);
  }

  return dates;
}

//displays cards for each available venue on a bootstrap accordian.
function displayVenuesOnDate(dateStr, data, i, partySize) {
  const map = "One,Two,Three,Four,Five,Six,Seven".split(",");
  const id = map[i];

  //create accordian item
  let accordianItem = document.createElement("div");
  accordianItem.className = "accordian-item new-element hover-focus";
  accordianItem.id = id;
  accordianItem.innerHTML = `
        <h2 class="accordion-header" id="panelsStayOpen-heading-${id}">
        <button id="title-${id}" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-${id}" aria-expanded="false" aria-controls="panelsStayOpen-collapse-${id}">      
        </button>
        </h2>
        <div id="panelsStayOpen-collapse-${id}" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading-${id}">
            <div id="body-${id}" class="accordion-body">     
            </div>
        </div>
    `;

  let accordian = document.getElementById("accordian");
  accordian.appendChild(accordianItem);

  let results = document.getElementById("accordian");
  results.appendChild(accordianItem);

  let title;
  if (data.length == 0) {
    //if no suitable venues then display message
    title =
      "<strong>" + dateStr + "</strong> &nbsp No suitable venues available";

    const button = document.getElementById(`title-${id}`);
    button.disabled = true;
    button.classList.add("unavailable");
  } else if (data.length == 1) {
    title = "<strong>" + dateStr + "</strong> &nbsp 1 venue available";
  } else {
    title =
      "<strong>" +
      dateStr +
      "</strong> &nbsp " +
      data.length +
      " venues available";
  }

  let venueGrid = document.createElement("div");
  venueGrid.className = "grid";
  for (let obj of data) {
    const stringCost = obj["Catering Cost pp"].slice(1);
    const totalCateringCost = +stringCost * partySize;
    const totalCateringCostStr = "£" + totalCateringCost;

    let venueCost = 0;
    const splitDate = dateStr.split(" ")[0].split("/");
    const date = new Date(splitDate[2], splitDate[1] - 1, +splitDate[0]);
    if (date.getDay() == 0 || date.getDay() == 6) {
      venueCost = +obj["Weekend Price"].slice(1);
    } else {
      venueCost = +obj["Weekday Price"].slice(1);
    }

    const totalCost = totalCateringCost + venueCost;
    //create card
    let card = document.createElement("div");
    card.className = "card hover-focus";
    card.innerHTML = `
        <img src="images/${obj.name}.jpg" class="card-img-top card-image">
        <div class="card-body">
            <h5 class="card-title">${obj.name}</h5>
            <div class="cost-label"> ${"Total Cost £" + totalCost} </div>
        </div>          
        </div>
        `;

    venueGrid.appendChild(card);

    let cardList = document.getElementById(`${id}card-list`);
    let li;
    li = document.createElement("li");
    li.className = "list-group-item";
    if (obj["Licensed"] == "Yes") {
      li.innerHTML = `<p class="text-success">Licensed <i class="bi bi-check-lg"></i></p>`;
    } else {
      li.innerHTML = `<p class="text-danger">Unlicensed <i class="bi bi-x-lg"></i></p>`;
    }
    card.appendChild(li);
    const keysToIterateThrough = [
      "Capacity",
      "Catering Cost pp",
      "Total Bookings",
    ];
    keysToIterateThrough.forEach((k) => {
      const v = obj[k];
      li = document.createElement("li");
      li.className = "list-group-item";
      li.innerHTML = `<p class="align-left">${k}</p><p class="align-right fw-bold">${v}</p>`;
      card.appendChild(li);
    });
    li = document.createElement("li");
    li.className = "list-group-item";
    li.innerHTML = `<p class="align-left">Weekday Price</p><p class="align-right fw-bold">${obj["Weekday Price"]}</p>`;
    card.appendChild(li);
    li = document.createElement("li");
    li.className = "list-group-item";
    li.innerHTML = `<p class="align-left">Weekend Price</p><p class="align-right fw-bold">${obj["Weekend Price"]}</p>`;
    card.appendChild(li);

    li = document.createElement("li");
    li.className = "list-group-item";
    li.innerHTML = `<p class="align-left">Total catering cost</p><p class="align-right fw-bold">${totalCateringCostStr}</p>`;
    card.appendChild(li);
  }

  document.getElementById(`title-${id}`).innerHTML = title;
  document.getElementById(`body-${id}`).appendChild(venueGrid);

  //open accordian if only one date selected
  if (id == "One" && !document.getElementById("date-range-checkbox").checked)
    document.getElementById("title-One")?.click();
}

//add on change listener to startDate input to ensure chosen dates are valid
document
  .getElementById("startDate")
  .addEventListener("change", updateMinMaxEndDate);

//ensure that the user cannot pick a date from the past my setting startDate min to today
const today = new Date();
const todayStr = today.toISOString().split("T")[0];
document.getElementById("startDate").min = todayStr;

//input validation for date range
function updateMinMaxEndDate() {
  if (!document.getElementById("date-range-checkbox").checked) return; //ignore if not inputting a date range
  if (form.elements["startDate"].value == "") return; //ignore if no start date inputted

  const startDateStr = document.getElementById("startDate").value;
  const startDate = new Date(startDateStr);

  let maxDate = new Date(startDate);

  maxDate.setDate(startDate.getDate() + 6);
  const maxDateStr = maxDate.toISOString().split("T")[0];

  document.getElementById("endDate").min = startDateStr;
  document.getElementById("endDate").max = maxDateStr;
}

function changeToSearchTab() {
  document.getElementById("venues-tab").click();
}

function toggleDateInput() {
  const checkBox = document.getElementById("date-range-checkbox");
  let endDate = document.getElementById("endDate");
  endDate.toggleAttribute("required");
  if (checkBox.checked == true) {
    updateMinMaxEndDate();
    endDate.style.visibility = "visible";
  } else {
    endDate.style.visibility = "hidden";
  }
}
