                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Código de Barra</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="filteredProducts.length === 0">
                            <td colspan="4" style="text-align: center;">
                                Nenhum produto encontrado
                            </td>
                        </tr>
                        <tr v-for="product in filteredProducts" :key="product.id">
                            <td>{{ product.name }}</td>
                            <td>R$ {{ formatPrice(product.price) }}</td>
                            <td>{{ product.barcode }}</td>
                            <td class="actions">
                                <button class="edit" @click="editProduct(product)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete" @click="deleteProduct(product.id)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
        <!-- Modal -->
        <div v-if="showModal" class="modal">
            <div class="modal-content">
                <span class="close" @click="closeModal">&times;</span>
                <form @submit.prevent="handleSave">
                    <input type="hidden" v-model="formData.id">
                    <input type="text" v-model="formData.name" placeholder="Nome" required>
                    <input type="number" v-model="formData.price" placeholder="Preço" step="0.01" required>
                    <input type="text" v-model="formData.barcode" placeholder="Código de Barra" required>
                    <button type="submit">Salvar</button>
                    <button type="button" @click="closeModal">Voltar</button>
                </form>
            </div>
        </div>
<!-- PROBLEMA COM O TOAST, CORRIGIR MAIS TARDE -->
    <!-- <script>
        const { createApp } = Vue;
        
        const API_URL = 'http://localhost:8383/api';

        const app = createApp({
            data() {
                return {
                    products: [],
                    filteredProducts: [],
                    searchQuery: '',
                    showModal: false,
                    selectedProduct: null,
                    sidebarVisible: true,
                    sidebarCollapsed: false,
                    showDropdown: false,
                    formData: {
                        id: null,
                        name: '',
                        price: '',
                        barcode: ''
                    }
                }
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
                        this.$toast.error('Erro ao carregar produtos.');
                    }
                },

                async apiRequest(method, endpoint, data = null) {
                    try {
                        const url = endpoint ? `${API_URL}${endpoint}` : API_URL;
                        const options = {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json'
                            }
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
                    this.filteredProducts = this.products.filter(product =>
                        product.name.toLowerCase().includes(query) ||
                        product.barcode.toLowerCase().includes(query)
                    );
                },

                openModal() {
                    this.formData = {
                        id: null,
                        name: '',
                        price: '',
                        barcode: ''
                    };
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                    this.formData = {
                        id: null,
                        name: '',
                        price: '',
                        barcode: ''
                    };
                },

                editProduct(product) {
                    this.formData = { ...product };
                    this.showModal = true;
                },

                async handleSave() {
                    try {
                        if (this.formData.id) {
                            await this.apiRequest('PUT', '/produto', this.formData);
                            const index = this.products.findIndex(p => p.id === this.formData.id);
                            if (index !== -1) {
                                console.log('dados do form:',this.formData);
                                this.products.splice(index, 1, { ...this.formData });
                            }
                            // this.$toast.success('Produto atualizado com sucesso!');
                            alert('Produto atualizado com sucesso!');
                        } else {
                            const newProduct = await this.apiRequest('POST', '/produto', this.formData);
                            this.products.push(newProduct);
                            // this.$toast.success('Produto cadastrado com sucesso!');
                            alert('Produto cadastrado com sucesso!');
                        }
                        
                        this.filterProducts();
                        this.closeModal();
                    } catch (error) {
                        console.error('Erro ao salvar produto:', error);
                        // this.$toast.error('Erro ao salvar produto. Tente novamente.');
                        alert('Erro ao salvar produto. Tente novamente.');
                    }
                },

                async deleteProduct(id) {
                    if (confirm('Tem certeza que deseja excluir este produto?')) {
                        try {
                            await this.apiRequest('DELETE', '/produto', { id });
                            this.products = this.products.filter(p => p.id !== id);
                            this.filterProducts();
                            // this.$toast.success('Produto excluído com sucesso!');
                            alert('Produto excluído com sucesso!');
                        } catch (error) {
                            console.error('Erro ao excluir produto:', error);
                            // this.$toast.error('Erro ao excluir produto. Tente novamente.');
                            alert('Erro ao excluir produto. Tente novamente.');
                        }
                    }
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
                    this.$toast.info('Usuário desconectado');
                    this.showDropdown = false;
                },

                formatPrice(price) {
                    return parseFloat(price).toFixed(2).replace('.', ',');
                },

                setupEventListeners() {
                    document.addEventListener('click', (e) => {
                        const userMenu = document.querySelector('.user');
                        const dropdown = document.querySelector('.dropdown');
                        
                        if (userMenu && dropdown && 
                            !userMenu.contains(e.target) && 
                            !dropdown.contains(e.target)) {
                            this.showDropdown = false;
                        }
                    });
                }
            }
        });

        // Registrar o plugin com o nome global correto
        if (window.VueToastificationPlugin) {
            app.use(window.VueToastificationPlugin.default || window.VueToastificationPlugin, {
                timeout: 3000,
                position: 'top-right',
                transition: 'Vue-Toastification__bounce',
                maxToasts: 5,
                newestOnTop: true
            });
        } else {
            console.warn('Vue Toastification não carregou corretamente. Verifique o CDN.');
        }

        app.mount('#app');
    </script> -->
