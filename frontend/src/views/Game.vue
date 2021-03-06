<template>
  <div class="container mx-auto">
    <parts-main-header class="italic my-2">Game</parts-main-header>
    <template v-if="!isStart">
      <app-create-user-form
        v-model="formValue"
        @game-start="clickFormButtonEventHandler"
      />
    </template>
    <template v-else>
      <app-game-area />
    </template>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, provide } from 'vue'
import AppCreateUserForm from '@/components/modules/game/AppCreateUserForm.vue'
import AppGameArea from '@/components/modules/game/AppGameArea.vue'
import PartsMainHeader from '@/components/parts/PartsMainHeader.vue'
import {
  usePlayer,
  UsePlayerType,
  PlayerFormType,
  GamePlayerStateKey,
} from '@/hooks/game/usePlayer'
import { IAppConfig } from '@/types'
/* eslint-disable-next-line @typescript-eslint/no-var-requires */
const config: IAppConfig = require('@/config/data')

export default defineComponent({
  name: 'Game',
  components: {
    AppCreateUserForm,
    AppGameArea,
    PartsMainHeader,
  },
  setup() {
    // data
    const playerService = usePlayer()

    // provide
    provide(GamePlayerStateKey, playerService)

    // computed
    const playerForm = computed(
      (): PlayerFormType => playerService.getPlayerForm()
    )

    const formValue = computed({
      get: (): string => playerService.getPlayerForm().name,
      set: (value: string) => {
        playerService.updateFormTextValue('name', value)
      },
    })

    const isStart = computed((): boolean => playerService.getIsStart())

    // methods
    /**
     * catch form button event handling
     * @param {Event} _
     * @return {void}
     */
    const clickFormButtonEventHandler = (_: Event) => {
      playerService.startGame()
    }

    return {
      playerForm,
      formValue,
      isStart,
      clickFormButtonEventHandler,
    }
  },
})
</script>
<style lang="scss" scoped>
.game {
  &__content-area {
    background-color: #f4f2db;
    padding: 20px 20px;
    margin: 10px 0;
    border-bottom: solid 3px #8f8d80;
    border-radius: 9px;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.22);
  }

  &__form-area {
    h2 {
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.22);
    }
  }

  &__form-content {
    background-color: #f4f2db;
    padding: 20px 20px;
    border-bottom: solid 3px #8f8d80;
    // border-radius: 9px;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.22);
  }
}
</style>
