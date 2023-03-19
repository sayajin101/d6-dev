
Vue.directive('select', {
    twoWay: true,
    bind: function (el, binding, vnode) {
        $(el).select2().on("select2:select", (e) => {
            // v-model looks for
            //  - an event named "change"
            //  - a value with property path "$event.target.value"
            console.log(e.target);
            el.dispatchEvent(new Event('change', { target: e.target }));
        });

        // on deselecting an option trigger change event
        $(el).on('select2:unselect', function (e) {
            // v-model looks for
            //  - an event named "change"
            //  - a value with property path "$event.target.value"
            el.dispatchEvent(new Event('change', { target: e.target }));
        });
    },
    componentUpdated: function(el, me) {
        // update the selection if the value is changed externally
        $(el).trigger("change");
    }
});


// Create Vue 2 instance
var app = new Vue({
    el: '#app',
    data: {
        invoices: [],
        customers: [],
        products: [],
        form: {
            customer_id: '',
            total: 0.00,
            paid: '0',
            due_date: '',
            items: [],
            comments: '',
            taxable: true,
            tax_rate: 15,
            tax_due: 0.00,
            other: 0.00,
            subtotal: 0.00
        }
    },
    methods: {
        getInvoices: function() {
            axios.get('/invoice/json')
                .then(response => {
                    this.invoices = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getProducts: function() {
            axios.get('/product/json')
                .then(response => {
                    this.products = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getCustomers: function() {
            axios.get('/customer/json')
                .then(response => {
                    this.customers = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        showInvoice: function(invoiceId) {
            window.location.href = '/invoice/' + invoiceId;
        },
        createInvoiceModal: function() {
            // Bootstrap 5 modal show
            let options = {
                keyboard: false,
                focus: true
            }

            const myModal = bootstrap.Modal.getOrCreateInstance('#createInvoiceModal', options)
            myModal.show();

            // Reset form
            this.resetCreateInvoiceForm();

            // Set focus to first input
            // document.getElementById('invoiceNumber').focus();

            // Set due date to 30 days from today
            this.form.due_date = moment().add(30, 'days').format('DD/MM/YYYY');
        },
        resetCreateInvoiceForm: function() {
            // Reset form
            this.form = {
                customer_id: '',
                total: 0.00,
                paid: '0',
                due_date: '',
                items: [],
                comments: '',
                taxable: true,
                tax_rate: 15,
                tax_due: 0.00,
                other: 0.00,
                subtotal: 0.00,
            }

            $('.selectpicker').val(null).trigger('change');
        },
        createInvoice: function() {
            // Send data to server
            axios.post('/invoice/create', this.form)
                .then(response => {
                    // Close modal
                    var myModal = bootstrap.Modal.getInstance(document.getElementById('createInvoiceModal'));
                    myModal.hide();

                    // Reset form
                    this.resetCreateInvoiceForm();

                    // Get invoices
                    this.getInvoices();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        calculateTotal: function() {
            let total = 0.00;
            
            // Loop through products
            this.form.items.forEach(product => {
                // Get product price
                let productPrice = this.products.find(productPrice => productPrice.id == product).amount;

                // Calculate total
                total += parseFloat(productPrice);
            });

            // Add tax if taxable
            if (this.form.taxable) {
                total += (total * (this.form.tax_rate / 100));

                // Set tax due
                this.form.tax_due = (total * (this.form.tax_rate / 100)).toFixed(2);
            }


            // Set subtotal
            this.form.subtotal = total.toFixed(2);

            // Set total
            this.form.total = total.toFixed(2);
        }
    },
    mounted() {
        this.getInvoices();
        this.getProducts();
        this.getCustomers();
    },
    filters: {
        timeStamp: function(value) {
            if (!value) return '';

            // Convert timestamp to human readable date
            return moment(value).format('Do MMM YYYY');
        },
        paidBoolean: function(value) {
            if (value == 0) {
                return 'No';
            } else {
                return 'Yes';
            }
        }
    }
});
