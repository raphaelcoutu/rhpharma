
const state = {
    selected: []
};

// getters
const getters = {
    //
};

// actions
const actions = {
    //
};

// mutations
const mutations = {
    addSelected(state, payload) {
        state.selected.push(payload)
    },
    removeSelected(state, index) {
        state.selected.splice(index, 1)
    },
    setSelected(state, payload) {
        state.selected = [];
    }

};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}