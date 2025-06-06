function renderServicesCard(services) {
	return services
		.map((el) => {
			return `
      <a href="${el.url}" class="service-card">
          <div class="card-icon d-flex-centered">
              <img src="${el.icon}" alt="${el.name}" />
          </div>
          <h5>${el.name}</h5>
          <p>
            ${el.desc}
          </p>
      </a>
    `;
		})
		.join("");
}

function renderDoctorsCard(doctors) {
	return doctors
		.map((el) => {
			return `
      <a href="${el.url}" class="doctor-card">
          <img src="${el.image}" alt="${el.name}" />
          <h5>${el.name}</h5>
          <span>${el.specialty}</span>
      </a>
    `;
		})
		.join("");
}

function ajaxCall(dataToSend, url, section) {
	$.ajax({
		url: url,
		method: "get",
		data: dataToSend,
		success: function (data) {
			// console.log(data);
			if (data.status == 0) {
				// console.log("remove all");
				section.innerHTML = "";
			}
			if (data.status == 1) {
				const returnedData = data.services || data.doctors;

				const dataToUse = JSON.parse(returnedData);

				// console.log(dataToUse);

				const newHTML =
					(data.doctors && renderDoctorsCard(dataToUse)) ||
					(data.services && renderServicesCard(dataToUse));

				// console.log(newHTML);
				section.innerHTML = newHTML;
			}
		},
		error: function (error) {
			console.log(error);
		},
	});
}

function searchFilterFormSubmit(url, form) {
	form.addEventListener("submit", (e) => {
		e.preventDefault();
		const dataToSend = { keyword: form.querySelector("input").value };

		const section = document.querySelector(".section-grid");
		ajaxCall(dataToSend, url, section);
	});
}

function searchResultsOnInputChange(url, input) {
	input.addEventListener("input", (e) => {
		// console.log(input.value);
		const dataToSend = { keyword: input.value };

		const section = document.querySelector(".section-grid");

		setTimeout(() => {
			ajaxCall(dataToSend, url, section);
		}, 1000);
	});
}

const servicesForm = document.querySelector("#search-services-form");
const servicesFormInput = document.querySelector("#search-services-form input");
const doctorsSearchForm = document.querySelector("#filter-doctors-form");
const doctorsSearchFormInput = document.querySelector(
	"#filter-doctors-form input"
);

servicesForm && searchFilterFormSubmit(filterServices, servicesForm);

doctorsSearchForm && searchFilterFormSubmit(filterDoctors, doctorsSearchForm);

servicesForm && searchResultsOnInputChange(filterServices, servicesFormInput);
doctorsSearchForm &&
	searchResultsOnInputChange(filterDoctors, doctorsSearchFormInput);
