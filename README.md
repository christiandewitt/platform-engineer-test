# Reconnect Backend Test

Backend test solution prepared by Christian de Witt

# Reviewer Notes
The following rules/filters are applied to the data set:
- Data is sanitized with unused fields removed
- Duplicates are removed using "strict" rules
- Grouped on “Title” with site and shoot times aggregated into a collection

**Please note:**
The URI to the movies data source is currently set to a static JSON file as provided for the test
- The ABQ API is down or timing out and

# Environmental Variables
Environmental Variables that need to be set:

| Variable | Description |
| ------ | ------ |
| PRODUCTIONS_API_URI | **Base URI** to the productions data source e.g. `https://c2t-cabq-open-data.s3.amazonaws.com` |

**Please note:**
- There is an .env.example file

# Endpoints

Endpoints available for use:

**Productions API**

Return the productions in the desired structure and in JSON format.

* **URL**

  /api/productions

* **Method:**

  `GET`
  
*  **URL Params**

   **Required:**
 
   `from=[date]`
   
   Format: YYYY-MM-DD

   **Required:**
 
   `to=[date]`
   
   Format: YYYY-MM-DD

* **Success Response:**

    **Code:** 200 
    
    **Content-Type:** `application/json`
    
    **Body:** `{
    	"count": 1,
    	"productions": [{
    		"title": "$5 a Day",
    		"type": "Movie",
    		"sites": [{
    			"name": "Chevron",
    			"shoot_dates": ["September 12, 2007"]
    		}, {
    			"name": "Stag Tobacconist",
    			"shoot_dates": ["September 12, 2007"]
    		}]
    	}]
    }`
 
* **Error Response:**

    **Code:** 422 
    
    **Content-Type:** `application/json`
    
    **Body:** `{
    	"errors": {
    		"from": ["The from does not match the format Y-m-d."]
    	}
    }`

**Productions View**

Render the productions view which is a simple bulleted layout.

* **URL**

  /productions

* **Method:**

  `GET`
  
*  **URL Params**

   **Required:**
 
   `from=[date]`
   
   Format: YYYY-MM-DD

   **Required:**
 
   `to=[date]`
   
   Format: YYYY-MM-DD

*  **Content-Type:** `text/html`

# Todos
- Unit tests
- Response parsing performance benchmarking
- HTTP request caching
- HTTP client env variables for config
- Optional from / to parameters with min / max constraints