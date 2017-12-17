<template>
    <div class="container">
        <table class="lists">
            <thead>
            <tdd>name</tdd>
            <td>nMarkets</td>
            <td>nMarketsWon</td>
            <td>nMarketsLost</td>
            <td>ratio</td>
            <td>sInOut</td>
            <td>aOdds</td>
            <td>aOddsWon</td>
            <td>aOddsLost</td>
            <td>hOddsWon</td>
            <td>hOddsLost</td>
            <td>lOddsWon</td>
            <td>lOddsLost</td>
            </thead>
            <tbody>
            <tr v-for="betType in betTypes">
                <td>
                    <span v-if="betType.lay">Against </span>
                    {{ betType.name }}
                </td>
                <td>{{ betType.nMarkets }}</td>
                <td>{{ betType.nMarketsWon }}</td>
                <td>{{ betType.nMarketsLost }}</td>
                <td>{{ betType.ratio }}%</td>
                <td>{{ betType.sInOut }}</td>
                <td>{{ betType.aOdds }}</td>
                <td>{{ betType.aOddsWon }}</td>
                <td>{{ betType.aOddsLost }}</td>
                <td>{{ betType.hOddsWon }}</td>
                <td>{{ betType.hOddsLost }}</td>
                <td>{{ betType.lOddsWon }}</td>
                <td>{{ betType.lOddsLost }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                betTypes: []
            }
        },
        mounted: function () {
            const args = {};
            StatsUtil.fetchFromApi('/api/stats/bet-types', args).then(function (betTypes) {
                this.betTypes = betTypes;
            }.bind(this));
        },
        methods: {}
    }
</script>