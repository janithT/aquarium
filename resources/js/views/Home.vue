<template>
  <div>
    <title-bar :title-stack="titleStack"/>
    <hero-bar :has-right-visible="false">
      Dashboard
    </hero-bar>
    <section class="section is-main-section">
      <tiles>
        <card-widget class="tile is-child" type="is-primary" icon="fish" :number="fishtempcount" label="Fish templates"/>
        <card-widget class="tile is-child" type="is-info" icon="turtle" :number="7770" label="Aquarium fish"/>
        <!-- <card-widget class="tile is-child" type="is-success" icon="chart-timeline-variant" :number="256" suffix="%" label="Performance"/> -->
      </tiles>

       

      <card-component title="Fish tank" class="has-table has-mobile-sort-spaced">
        <img src="images/abc.jpeg">
        <!-- <clients-table-sample data-url="/fish_templates"/> -->
      </card-component>
    </section>
  </div>
</template>

<script>
// @ is an alias to /src
import * as chartConfig from '@/components/Charts/chart.config'
import TitleBar from '@/components/TitleBar'
import HeroBar from '@/components/HeroBar'
import Tiles from '@/components/Tiles'
import CardWidget from '@/components/CardWidget'
import CardComponent from '@/components/CardComponent'
import LineChart from '@/components/Charts/LineChart'
import ClientsTableSample from '@/components/ClientsTableSample'
export default {
  name: 'home',
  components: {
    ClientsTableSample,
    LineChart,
    CardComponent,
    CardWidget,
    Tiles,
    HeroBar,
    TitleBar
  },
  data () {
    return {
      fishtempcount: 0,
      defaultChart: {
        chartData: null,
        extraOptions: chartConfig.chartOptionsMain
      }
    }
  },
  computed: {
    titleStack () {
      return [
        'Admin',
        'Dashboard'
      ]
    }
  },
  mounted () {
    this.getStatistics()

    this.$buefy.snackbar.open({
      message: 'Welcome back',
      queue: false
    })
  },
  methods: {
    randomChartData (n) {
      let data = []

      for (let i = 0; i < n; i++) {
        data.push(Math.round(Math.random() * 200))
      }

      return data
    },

    getStatistics(){
      this.isLoading = true
      axios
        .get('/dashboard/dashstats')
        .then(r => {
          this.isLoading = false
          if (r.data) {

            this.fishtempcount = r.data ?? 0
 
          }
        })
        .catch( err => {
          this.isLoading = false
          this.$buefy.toast.open({
            message: `Error: ${err.message}`,
            type: 'is-danger',
            queue: false
          })
        })
    }
 
  }
}
</script>
