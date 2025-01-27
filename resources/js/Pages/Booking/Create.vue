<template>
    <form @submit.prevent="submitBooking">
      <!-- Other form fields -->
  
      <!-- Stripe Payment Form -->
      <div class="stripe-card">
        <div id="card-number" class="stripe-element"></div>
        <div id="card-expiry" class="stripe-element"></div>
        <div id="card-cvc" class="stripe-element"></div>
      </div>
      <div id="card-errors" role="alert" class="stripe-card-errors"></div>
  
      <button type="submit">Book Now</button>
    </form>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { loadStripe } from '@stripe/stripe-js';
  import { router } from '@inertiajs/vue3';
  
  const stripePromise = loadStripe('pk_test_51KNyHdSDGjAmjQxjhTSx7hrI5uBOE75wqXRZNFdw83XSTU8gvwpFusz2W8FAy7PkV8K0aGHqhC620kbMWTg32ZpS00p3ktAi86'); // Replace with your Stripe publishable key
  
  const customer = ref({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    additional_info: '',
  });
  
  const pickupDate = ref('');
  const returnDate = ref('');
  const pickupLocation = ref('');
  const returnLocation = ref('');
  const totalDays = ref(0);
  const basePrice = ref(0);
  const extraCharges = ref(0);
  const taxAmount = ref(0);
  const totalAmount = ref(0);
  const extras = ref([]);
  
  let stripe;
  let elements;
  let cardNumber;
  let cardExpiry;
  let cardCvc;
  
  onMounted(async () => {
    stripe = await stripePromise;
  
    // Customize the appearance of the card elements
    const style = {
      base: {
        color: '#32325d',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#aab7c4',
        },
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a',
      },
    };
  
    elements = stripe.elements();
  
    // Create individual card elements
    cardNumber = elements.create('cardNumber', { style: style });
    cardExpiry = elements.create('cardExpiry', { style: style });
    cardCvc = elements.create('cardCvc', { style: style });
  
    // Mount the elements to their respective containers
    cardNumber.mount('#card-number');
    cardExpiry.mount('#card-expiry');
    cardCvc.mount('#card-cvc');
  
    // Handle real-time validation errors
    const displayError = document.getElementById('card-errors');
    cardNumber.on('change', (event) => {
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });
  
    cardExpiry.on('change', (event) => {
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });
  
    cardCvc.on('change', (event) => {
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });
  });
  
  const addExtra = () => {
    extras.value.push({ name: '', price: 0, description: '' });
  };
  
  const submitBooking = async () => {
    const { error, paymentMethod } = await stripe.createPaymentMethod({
      type: 'card',
      card: cardNumber,
      billing_details: {
        name: `${customer.value.first_name} ${customer.value.last_name}`,
        email: customer.value.email,
        phone: customer.value.phone,
      },
    });
  
    if (error) {
      console.error(error);
      return;
    }
  
    const response = await axios.post('/booking', {
      customer: customer.value,
      pickup_date: pickupDate.value,
      return_date: returnDate.value,
      pickup_location: pickupLocation.value,
      return_location: returnLocation.value,
      total_days: totalDays.value,
      base_price: basePrice.value,
      extra_charges: extraCharges.value,
      tax_amount: taxAmount.value,
      total_amount: totalAmount.value,
      extras: extras.value,
      payment_method_id: paymentMethod.id,
    });
  
    const clientSecret = response.data.clientSecret;
  
    const { paymentIntent, error: confirmError } = await stripe.confirmCardPayment(clientSecret, {
      payment_method: paymentMethod.id,
    });
  
    if (confirmError) {
      console.error(confirmError);
      return;
    }
  
    router.visit(`/booking/success?payment_intent=${paymentIntent.id}`);
  };
  </script>
  
  <style>
  .stripe-card {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 16px;
  }
  
  .stripe-element {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .stripe-card-errors {
    color: #ff3860;
    font-size: 14px;
    margin-bottom: 16px;
  }
  
  button {
    background-color: #4a90e2;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
  }
  
  button:hover {
    background-color: #357abd;
  }
  </style>