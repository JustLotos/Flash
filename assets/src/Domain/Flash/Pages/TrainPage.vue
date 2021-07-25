<template>
    <v-flex>
        <v-row justify="center" align="center" class="ma-0 pa-0">
            <v-col cols="12" sm="10" md="8" lg="6">
                <v-card color="primary" class="pt-5 pr-5 pl-5">
                    <v-row justify="center"  no-gutters>
                        <v-col cols="12" sm="12" class="mb-5">
                            <v-sheet color="light" min-height="200px">{{ currentCard.getFrontData }}</v-sheet>
                        </v-col>
                        <v-row justify="center" no-gutters>
                            <v-divider :inset="true"></v-divider>
                        </v-row>
                        <v-col cols="12" sm="12" class="mt-5">
                            <v-sheet color="light" min-height="200px">
                                <control-editor
                                    v-if="!hidden"
                                    v-model="currentCard.getBackData"
                                    readonly="true"
                                />
                            </v-sheet>
                        </v-col>
                    </v-row>

                  <v-row class="mb-6" justify="center" no-gutters>
                    <v-card-actions>
                      <v-btn v-if="hidden" @click="toggleSide">Показать ответ</v-btn>
                      <v-item-group v-else>
                        <v-btn @click="handleAnswer('0.5')">Плохо</v-btn>
                        <v-btn @click="handleAnswer('1')">Нормально</v-btn>
                        <v-btn @click="handleAnswer('1.5')">Хорошо</v-btn>
                      </v-item-group>
                    </v-card-actions>
                  </v-row>
                </v-card>
            </v-col>
        </v-row>
    </v-flex>
</template>

<script lang="ts">
import AnswerPanel from "../Components/AnswerPanel/AnswerPanel.vue";
import {Deck} from "../Modules/Deck/Deck";
import GetDeckDTO from "../DTO/GetDeckDTO";
import {DeckModule} from "../Modules/Deck/DeckModule";
import Router from "../../App/Router";
import {CardModule} from "../Modules/Card/CardModule";
import Card from "../Modules/Card/Card";
import ControlEditor from "../../App/Components/FormElements/ControlEditor.vue";
export default {
    name: "TrainPage",
    components: {ControlEditor},
    data: function () {
      return {
        hidden: true,
        cardGenerator: {},
        card: new Card,
        deck: new Deck(),
        switchFlag: false
      };
    },
    computed: {
      currentCard: function (): Card {
        return this.card;
      }
    },
    methods: {
      setDeck: function (deck) { this.deck = deck },
      setCard: function (card: Card) { this.card = card },
      nextCard: function () {
        this.card = new Card();
        let item = this.cardGenerator.next();
        console.log(item);
        if(!item.done) {
          this.card = CardModule.cardById(item.value);
          console.log(this.card);
          return true;
        }

        return false
      },
      setCardGenerator: function (gen) { this.cardGenerator = gen() },
      toggleSide: function() { this.hidden = !this.hidden; },
      handleAnswer: function (answerEval) {
        this.toggleSide();
        this.nextCard();
        console.log(answerEval);
      },
    },
    mounted() {
      this.nextCard();
    },
    async beforeRouteEnter(to, from, next) {
      let deck: Deck = new Deck(to.params as Deck);
      let dto: GetDeckDTO = new GetDeckDTO(deck, GetDeckDTO.FOR_LEARN);

      await DeckModule.get(dto).catch(() => { Router.push({name: 'Collection'}) });

      next(function (vm) {
        let fetchedDeck: Deck = DeckModule.deckById(deck.getId());
        vm.setDeck(fetchedDeck);
        vm.setCardGenerator(fetchedDeck.forRepeat())
      });
    }
}
</script>