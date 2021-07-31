class GroupManager {
    constructor() {
        this.groups = [];
    }

    getOtherGroups(theGroup) {
        var others = [];
        this.groups.forEach(function(group) {
            if (group != theGroup) {
                others.push(group);
            }
        });

        return others;
    }

    bind(action, otherGroups) {
        var _this = this;

        this.groups.forEach(function(group) {
            var otherGroups = _this.getOtherGroups();
            action(group, otherGroups);
        });
    }

    add(group) {
        this.groups.push(group);
    }
};