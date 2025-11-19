
                <div class="product-grid">
                    <div v-if="filteredProducts.length === 0" style="grid-column: 1 / -1; text-align: center;">
                        Nenhum produto encontrado
                    </div>
                    <div v-for="product in filteredProducts" :key="product.id" class="product-card">
                        <h3>{{ product.name }}</h3>
                        <p>R$ {{ formatPrice(product.price) }}</p>
                        <p>{{ product.barcode }}</p>
                        <button @click="addToCart(product)">
                            <i class="fas fa-plus"></i> Adicionar
                        </button>
                    </div>
                </div>
            

            <div class="cart" :class="{ 'hidden': !cartVisible }">
                <div class="cart-header">
                    <h2>Carrinho</h2>
                    <button class="cart-toggle-btn" @click="toggleCart">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div v-if="cartItems.length === 0" style="text-align: center;">
                    Carrinho vazio
                </div>
                <div v-for="item in cartItems" :key="item.id" class="cart-item">
                    <span>{{ item.name }}</span>
                    <span>R$ {{ formatPrice(item.price) }}</span>
                    <button @click="removeFromCart(item.id)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="cart-total">
                    Total: R$ {{ formatPrice(cartTotal) }}
                </div>
                <button class="finalize-btn" @click="finalizePurchase" :disabled="cartItems.length === 0">
                    Finalizar
                </button>
            </div>
        
    
    <!-- <script>
        const { createApp } = Vue;
        const API_URL = 'http://localhost:8383/api';

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
    </script> -->
