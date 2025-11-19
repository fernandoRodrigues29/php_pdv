        const { createApp } = Vue;
        const API_URL = 'http://localhost:8383/api.php/api';

        const app = createApp({
            data() {
                return {
                    products: [],
                    filteredProducts: [],
                    searchQuery: '',
                    cartItems: [],
                    cartVisible: true,
                    sidebarVisible: true,
                    sidebarCollapsed: false,
                    showDropdown: false,
                };
            },
            computed: {
                cartTotal() {
                    return this.cartItems.reduce((total, item) => total + parseFloat(item.price), 0);
                },
            },
            async mounted() {
                await this.loadProducts();
                this.setupEventListeners();
            },
            methods: {
                async loadProducts() {
                    try {
                        const response = await this.apiRequest('GET', '');
                        this.products = response.data.data || [];
                        this.filteredProducts = [...this.products];
                    } catch (error) {
                        console.error('Erro ao carregar produtos:', error);
                        alert('Erro ao carregar produtos.');
                    }
                },

                async apiRequest(method, endpoint, data = null) {
                    try {
                        const url = endpoint ? `${API_URL}${endpoint}` : API_URL;
                        const options = {
                            method: method,
                            headers: { 'Content-Type': 'application/json' },
                        };
                        if (data) {
                            options.body = JSON.stringify(data);
                        }
                        const response = await fetch(url, options);
                        if (!response.ok) {
                            throw new Error(`Erro na requisição: ${response.status}`);
                        }
                        return await response.json();
                    } catch (error) {
                        console.error('Erro na API:', error);
                        throw error;
                    }
                },

                filterProducts() {
                    if (!this.searchQuery.trim()) {
                        this.filteredProducts = [...this.products];
                        return;
                    }
                    const query = this.searchQuery.toLowerCase();
                    this.filteredProducts = this.products.filter(
                        (product) =>
                            product.name.toLowerCase().includes(query) ||
                            product.barcode.toLowerCase().includes(query)
                    );
                },

                addToCart(product) {
                    this.cartItems.push({ ...product });
                    alert(`${product.name} adicionado ao carrinho!`);
                },

                removeFromCart(id) {
                    this.cartItems = this.cartItems.filter((item) => item.id !== id);
                    alert('Produto removido do carrinho!');
                },

                async finalizePurchase() {
                    if (this.cartItems.length === 0) return;
                    try {
                        const purchaseData = {
                            items: this.cartItems,
                            total: this.cartTotal,
                        };
                        await this.apiRequest('POST', '/purchase', purchaseData);
                        alert('Compra finalizada com sucesso!');
                        this.cartItems = [];
                    } catch (error) {
                        console.error('Erro ao finalizar compra:', error);
                        alert('Erro ao finalizar compra. Tente novamente.');
                    }
                },

                toggleCart() {
                    this.cartVisible = !this.cartVisible;
                },

                toggleSidebar() {
                    this.sidebarVisible = !this.sidebarVisible;
                },

                toggleSidebarCollapse() {
                    this.sidebarCollapsed = !this.sidebarCollapsed;
                },

                toggleDropdown() {
                    this.showDropdown = !this.showDropdown;
                },

                logout() {
                    alert('Usuário desconectado');
                    this.showDropdown = false;
                },

                formatPrice(price) {
                    return parseFloat(price).toFixed(2).replace('.', ',');
                },

                setupEventListeners() {
                    document.addEventListener('click', (e) => {
                        const userMenu = document.querySelector('.user');
                        const dropdown = document.querySelector('.dropdown');
                        if (
                            userMenu &&
                            dropdown &&
                            !userMenu.contains(e.target) &&
                            !dropdown.contains(e.target)
                        ) {
                            this.showDropdown = false;
                        }
                    });
                },
            },
        });

        if (window.VueToastificationPlugin) {
            app.use(window.VueToastificationPlugin.default || window.VueToastificationPlugin, {
                timeout: 3000,
                position: 'top-right',
                transition: 'Vue-Toastification__bounce',
                maxToasts: 5,
                newestOnTop: true,
            });
        } else {
            console.warn('Vue Toastification não carregou corretamente. Verifique o CDN.');
        }

        app.mount('#app');