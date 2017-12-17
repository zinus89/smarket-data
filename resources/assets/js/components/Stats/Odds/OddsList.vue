
<template>
    <div class="container">
        <table class="lists">
            <thead>
            <td>Odds</td>
            <td>Lay</td>
            <td>nBets</td>
            <td>sBackersStake</td>
            <td>nMarkets</td>
            <td>nMarketsWon</td>
            <td>nMarketsLost</td>
            <td>sInOut</td>
            </thead>
            <!--<tbody>-->
            <!--<tr v-for="odds in oddsData">-->
               <!--<td>-->
               <!--</td>-->
               <!--<td>{{ betType.nMarkets }}</td>-->
               <!--<td>{{ betType.nMarketsWon }}</td>-->
               <!--<td>{{ betType.nMarketsLost }}</td>-->
               <!--<td>{{ betType.sInOut }}</td>-->
               <!--<td>{{ betType.aOdds }}</td>-->
               <!--<td>{{ betType.aOddsWon }}</td>-->
               <!--<td>{{ betType.aOddsLost }}</td>-->
            <!--</tr>-->
            <!--</tbody>-->
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                oddsData: []
            }
        },
//        mounted: function() {
//            const args = {};
//            StatsUtil.fetchFromApi('/api/stats/odds',args).then(function(oddsData){
//                this.oddsData = oddsData;
//            }.bind(this));
//        },
        methods: {
        }
    }
</script>