<script>
import Helper from '../assets/Helper';

export default {
    components: {
        //
    },
    props: {
        state: {
            type: Object,
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
        //
        
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
        
    <div className="col card-custom pt-3 pb-3">
            <div className="card h-100">
                <img v-bind:src="'img/' + this.state.order.image" className="card-img-top"/>
                <div className="card-body text-center">
                    <h5 className="card-title">{{ this.state.order.plate }}</h5>
                    <p>Brand: {{ this.state.order.brand }}</p>
                    <p>Year: {{ this.state.order.year }}</p>
                    <p>Capacity: {{ this.state.order.capacity }}</p>
                    <p>Price: €{{ this.state.order.price }} / 1 month</p>
                </div>
            </div>
    </div>

</template>
