<a name="readme-top"></a>

<br />
<div align="center">


  <h1 align="center">Rahal (رحال)</h1>
</div>

<div align="center">

![PHP](https://img.shields.io/badge/Backend-PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Frontend-Bootstrap%205-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Swiper.js](https://img.shields.io/badge/Slider-Swiper.js-6332F6?style=for-the-badge&logo=swiper&logoColor=white)
![Chatbase](https://img.shields.io/badge/AI-Chatbot-000000?style=for-the-badge&logo=openai&logoColor=white)

</div>

---

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li><a href="#key-features">Key Features</a></li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>

---

## 📖 About The Project

**Rahal** is a comprehensive web platform dedicated to promoting tourism in the **Kingdom of Saudi Arabia**. It bridges the gap between travelers and the Kingdom's rich heritage by providing a seamless interface to explore historical landmarks, modern cities, and diverse cultural experiences.

The platform leverages a dynamic PHP backend to serve real-time data about attractions, prayer times, and geographical information, ensuring tourists have a reliable companion during their journey.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

### 🛠️ Built With

This project was built using the following technologies:

* **Frontend:**
    * ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white)
    * ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white)
    * ![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
    * ![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=flat-square&logo=javascript&logoColor=black)
    * **Swiper.js** (For responsive carousels)

* **Backend:**
    * ![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat-square&logo=php&logoColor=white)
    * ![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql&logoColor=white)

* **Integrations:**
    * **Chatbase AI** (Smart Chatbot Assistant)
    * **Google Fonts** (Tajawal Font)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## ✨ Key Features

* **🗺️ Interactive Kingdom Map:** Explore Saudi Arabia's regions interactively. Hover over provinces to uncover details.
* **🕌 Smart Tourism Database:** Dynamic fetching of landmarks categorized by type (Historical, Religious, Nature, etc.).
* **🤖 AI Assistant:** Integrated AI chatbot to answer tourist queries instantly, 24/7.
* **🕋 Qibla & Prayer Times:** Essential utilities for Muslim travelers to find prayer directions and times.
* **🔍 Advanced Search:** Find specific cities or attractions effortlessly.
* **📱 Fully Responsive:** Optimized experience for Desktop, Tablet, and Mobile devices.
* **💬 User Reviews:** Read and submit reviews about visited locations.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## 🚀 Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

You need a local server environment to run PHP files.
* **XAMPP** / **WAMP** / **MAMP**
* A code editor (VS Code recommended)

### Installation

1.  **Clone the repo**
    ```sh
    git clone [https://github.com/your-username/rahal-project.git](https://github.com/your-username/rahal-project.git)
    ```
2.  **Move to Server Directory**
    * Place the project folder inside `htdocs` (if using XAMPP) or `www` (if using WAMP).
3.  **Database Setup**
    * Open `phpMyAdmin` (usually at `http://localhost/phpmyadmin`).
    * Create a new database named `rahal_db`.
    * Import the provided SQL file located in the root directory (e.g., `database.sql`).
4.  **Configure Connection**
    * Open `assets/php/db_connect.php`.
    * Update your database credentials:
    ```php
    $servername = "localhost";
    $username = "root";
    $password = ""; // Default XAMPP password is empty
    $dbname = "rahal_db";
    ```
5.  **Run the Project**
    * Open your browser and visit: `http://localhost/rahal-project/index.php`

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## 📸 Usage

The homepage serves as the central hub:
1.  **Search:** Use the top bar to find landmarks.
2.  **Carousel:** Swipe through major cities to see curated attractions.
3.  **Map:** Scroll down to the interactive map to visualize the Kingdom's geography.
4.  **Chat:** Click the bottom-right bubble to ask the AI assistant for help.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## 🤝 Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## 📞 Contact

**Rahal Team** - info@rahal.eny.sa

Project Link: [https://rahal.eny.sa/](https://rahal.eny.sa/)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

<p align="center">
  Made with ❤️ for the <strong>Kingdom of Saudi Arabia</strong> 🇸🇦
</p>
