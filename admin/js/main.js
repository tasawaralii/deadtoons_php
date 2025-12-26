function imdb_search() {
    var s = document.getElementById('imdb_s').value; // Corrected .value() to .value
    var url = "https://www.omdbapi.com/?apikey=744969b6&s=" + s;

    fetch(url) // Corrected fetch(s) to fetch(url)
    .then(response => response.json())
    .then(data => { // Corrected syntax: .then(data => {
        var results = document.getElementById('results'); // Assuming you have a div with id 'results'
        results.innerHTML = ''; // Clear previous results
        data.Search.forEach(movie => { // Corrected foreach to forEach and data.search to data.Search
            var div = document.createElement('div'); // Create a div for each movie
            var img = document.createElement('img'); // Create an image element
            img.src = movie.Poster; // Set the image source to the movie poster
            img.style.width = "200px";
            var title = document.createElement('p'); // Create a paragraph for the title
            title.textContent = movie.Title; // Set the paragraph text to the movie title

            div.appendChild(img); // Append the image to the div
            div.appendChild(title); // Append the title to the div
            results.appendChild(div); // Append the div to the results container
        });
    })
    .catch(error => console.error('Error:', error)); // Add error handling
}
