document.addEventListener('DOMContentLoaded', () => {
    const vehicleListings = document.getElementById('vehicle-listings');
    const filterForm = document.getElementById('filter-form');
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const sortBySelect = document.getElementById('sort-by');
    const paginationContainer = document.getElementById('pagination');
    const generalInfoTabs = document.getElementById('general-info-tabs');
    const contentTitle = document.getElementById('content-title');
    const priceRange = document.getElementById('price-range');
    const priceDisplay = document.getElementById('price-display');
    const siteLogo = document.getElementById('site-logo');
    const siteName = document.getElementById('site-name');
    const footerSiteName = document.getElementById('footer-site-name');
    const currentYear = document.getElementById('current-year');

    let currentFilters = {};
    let currentSort = 'relevance';
    let currentPage = 1;
    const itemsPerPage = 9; // Número de vehículos por página

    // Función para obtener y aplicar la configuración de personalización
    async function applyCustomization() {
        try {
            const response = await fetch('/api/settings'); // Endpoint para obtener la configuración
            const settings = await response.json();

            // Aplicar colores
            document.documentElement.style.setProperty('--primary-color', settings.primaryColor |
| '#007bff');
            document.documentElement.style.setProperty('--secondary-color', settings.secondaryColor |
| '#6c757d');
            document.documentElement.style.setProperty('--accent-color', settings.accentColor |
| '#28a745');

            // Aplicar logo y nombre del sitio
            siteLogo.src = settings.logoUrl |
| 'media/default_logo.png';
            siteName.textContent = settings.siteName |
| 'AutoMarket Latino';
            footerSiteName.textContent = settings.siteName |
| 'AutoMarket Latino';

            // Actualizar año en el footer
            currentYear.textContent = new Date().getFullYear();

            console.log('Configuración de personalización aplicada:', settings);
        } catch (error) {
            console.error('Error al cargar la configuración de personalización:', error);
        }
    }

    // Función para cargar vehículos
    async function loadVehicles() {
        try {
            // Construir URL de la API con filtros, ordenamiento y paginación
            const queryParams = new URLSearchParams({
               ...currentFilters,
                sortBy: currentSort,
                page: currentPage,
                limit: itemsPerPage
            }).toString();

            const response = await fetch(`/api/vehicles?${queryParams}`);
            const data = await response.json();
            const vehicles = data.vehicles;
            const totalVehicles = data.total;

            vehicleListings.innerHTML = ''; // Limpiar listados existentes

            if (vehicles.length === 0) {
                vehicleListings.innerHTML = '<p class="col-12 text-center">No se encontraron vehículos que coincidan con los criterios.</p>';
                paginationContainer.innerHTML = '';
                return;
            }

            vehicles.forEach(vehicle => {
                const vehicleCard = `
                    <div class="col">
                        <div class="card vehicle-card h-100">
                            <img src="${vehicle.imageUrl |
| 'https://via.placeholder.com/400x200?text=Auto'}" class="card-img-top" alt="${vehicle.make} ${vehicle.model}">
                            <div class="card-body">
                                <h5 class="card-title">${vehicle.make} ${vehicle.model}</h5>
                                <p class="card-text">Año: ${vehicle.year}</p>
                                <p class="card-text">Kilometraje: ${vehicle.mileage} km</p>
                                <p class="card-text">Ubicación: ${vehicle.location}</p>
                                <p class="card-text price">$${vehicle.price.toLocaleString('es-MX')}</p>
                                <a href="/vehicle/${vehicle.id}" class="btn btn-primary w-100">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                `;
                vehicleListings.insertAdjacentHTML('beforeend', vehicleCard);
            });

            updatePagination(totalVehicles);

        } catch (error) {
            console.error('Error al cargar los vehículos:', error);
            vehicleListings.innerHTML = '<p class="col-12 text-center text-danger">Hubo un error al cargar los vehículos. Por favor, inténtalo de nuevo más tarde.</p>';
        }
    }

    // Función para actualizar la paginación
    function updatePagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        paginationContainer.innerHTML = '';

        if (totalPages <= 1) return;

        const createPaginationItem = (page, text, isDisabled = false, isActive = false) => {
            const li = document.createElement('li');
            li.className = `page-item ${isDisabled? 'disabled' : ''} ${isActive? 'active' : ''}`;
            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = text;
            a.addEventListener('click', (e) => {
                e.preventDefault();
                if (!isDisabled &&!isActive) {
                    currentPage = page;
                    loadVehicles();
                }
            });
            li.appendChild(a);
            return li;
        };

        paginationContainer.appendChild(createPaginationItem(currentPage - 1, 'Anterior', currentPage === 1));

        for (let i = 1; i <= totalPages; i++) {
            paginationContainer.appendChild(createPaginationItem(i, i, false, i === currentPage));
        }

        paginationContainer.appendChild(createPaginationItem(currentPage + 1, 'Siguiente', currentPage === totalPages));
    }

    // Manejador de envío del formulario de filtros
    filterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        currentFilters = {
            condition: document.getElementById('condition').value,
            brand: document.getElementById('brand').value,
            year: document.getElementById('year').value,
            maxPrice: priceRange.value
        };
        currentPage = 1; // Resetear a la primera página al aplicar filtros
        loadVehicles();
    });

    // Manejador de búsqueda
    searchButton.addEventListener('click', () => {
        currentFilters.search = searchInput.value;
        currentPage = 1;
        loadVehicles();
    });

    // Manejador de ordenamiento
    sortBySelect.addEventListener('change', () => {
        currentSort = sortBySelect.value;
        currentPage = 1;
        loadVehicles();
    });

    // Manejador para el rango de precios
    priceRange.addEventListener('input', () => {
        priceDisplay.textContent = `$0 - ${parseInt(priceRange.value).toLocaleString('es-MX')}`;
    });

    // Manejador de pestañas de información general
    generalInfoTabs.addEventListener('click', (e) => {
        if (e.target.tagName === 'A') {
            e.preventDefault();
            const tab = e.target.dataset.tab;
            // Aquí se podría cargar contenido dinámicamente o mostrar/ocultar secciones
            // Por simplicidad, solo cambiaremos el título y mostraremos un mensaje
            contentTitle.textContent = e.target.textContent;
            vehicleListings.innerHTML = `<p class="col-12 text-center">Contenido para "${e.target.textContent}" se mostraría aquí.</p>`;
            paginationContainer.innerHTML = ''; // Ocultar paginación para estas secciones
        }
    });

    // Cargar marcas dinámicamente (ejemplo)
    async function loadBrands() {
        try {
            const response = await fetch('/api/brands'); // Suponiendo un endpoint para marcas
            const brands = await response.json();
            const brandSelect = document.getElementById('brand');
            brands.forEach(brand => {
                const option = document.createElement('option');
                option.value = brand.name;
                option.textContent = brand.name;
                brandSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error al cargar las marcas:', error);
        }
    }

    // Inicializar la aplicación
    applyCustomization();
    loadBrands();
    loadVehicles();
});
