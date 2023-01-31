<script>
import Helper from '../assets/Helper';
// import { RouterLink } from 'vue-router'

export default {
    components: {
        // RouterLink
    },
    props: {
        state: {
            type: Object,
            required: true
        },
        cars: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            //
        }
    },
    computed: {
        //
    },
    methods: {
        Order(plate, price, year, image, brand, capacity)
        {
            this.state.order = {
                plate: plate,
                price: price,
                year: year,
                image: image,
                brand: brand,
                capacity: capacity
            };

            this.$router.push('/payment');
        }
    },
    watch: {
        //
    },
    mounted() {
        //
    },
    beforeMount() {
        //
    }
}

</script>

<template>
    <div className="row row-cols-1 row-cols-md-3 g-4">

        <div v-for="(e, i) in cars" :key="'card' + i" class="col card-custom">
        <div class="card h-100">
            <img :src="`img/${e.image}`" class="card-img-top"/>
            <div class="card-body text-center">
            <h5 class="card-title">{{ e.plate }}</h5>
            <p>Brand: {{ e.brand }}</p>
            <p>Year: {{ e.year }}</p>
            <p>Capacity: {{ e.capacity }}</p>
            <p>Price: €{{ e.price }} / 1 month</p>
            <h6 :class="{ 'text-success': e.avaiable === 1, 'text-danger': e.avaiable !== 1 }">
                {{ e.avaiable === 1 ? 'AVAILABLE' : 'RENTED OUT' }}
            </h6>
            <button v-if="e.avaiable === 1" @click="Order(e.plate, e.price, e.year, e.image, e.brand, e.capacity)" class="btn btn-success">Rent NOW</button>
            </div>
        </div>
        </div>

    </div>
</template>
