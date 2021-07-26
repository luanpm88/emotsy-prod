class GroupManager {
    constructor() {
        this.groups = [];
    }

    bind(action) {
        this.groups.forEach(function(group) {
            action(group);
        });
    }

    add(group) {
        this.groups.push(group);
    }
};