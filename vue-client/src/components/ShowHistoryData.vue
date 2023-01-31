<script>
import Helper from '../assets/Helper';

export default {
    components: {
        //
    },
    props: {
        history: {
            type: Array,
            required: true
        },
        
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
    <table className="table table-hover bg-light">
        <colgroup>
            <col span="1" style="width: 3%;">
            <col span="1" style="width: 30%;">
            <col span="1" style="width: 30%;">
            <col span="1" style="width: 24%;">
            <col span="1" style="width: 3%;">
        </colgroup>
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">ID</th>
            <th scope="col">Date</th>
            <th scope="col">Car</th>
            <th scope="col">Price</th>
            </tr>
        </thead>
        <tbody>

            <tr v-for="(e, i) in history" :key="'history' + i">
                <th scope="row">{{ i + 1 }}</th>
                <td>{{ e.p_id }}</td>
                <td>{{ e.date }}</td>
                <td>{{ e.plate }}</td>
                <td>€{{ e.price }}</td>
            </tr>

        </tbody>

    </table>
</template>
