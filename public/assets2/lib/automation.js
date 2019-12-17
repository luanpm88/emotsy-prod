class AutomationElement {
    static fromJson(json, container, events = {} ) {
        var trigger;

        if (events.onclick == null) {
            events.onclick = function() {};
        }

        if (events.onhover == null) {
            events.onhover = function() {};
        }

        if (events.onadd == null) {
            events.onadd = function() {};
        }

        if (events.onaddyes == null) {
            events.onaddyes = function() {};
        }

        if (events.onaddno == null) {
            events.onaddno = function() {};
        }

        var list = [];
        var i;
        var e;
        // Initiate objects
        for (i = 0; i < json.length; i += 1) {
            e = json[i];
            if (e.type == 'ElementTrigger') {
                list[e.id] = new ElementTrigger(e);
                list[e.id].draw(400, 100, container);
                list[e.id].setPosition(1, 0);
                trigger = list[e.id];

                trigger.onclick(events.onclick);
                trigger.onhover(events.onhover);
                trigger.onadd(events.onadd);

                // add to events bucket
                trigger.events['onclick'] = events.onclick;
                trigger.events['onhover'] = events.onhover;
                trigger.events['onadd'] = events.onadd;
                trigger.events['onaddyes'] = events.onaddyes;
                trigger.events['onaddno'] = events.onaddno;
            } else if (e.type == 'ElementAction') {
                list[e.id] = new ElementAction(e);
            } else if (e.type == 'ElementWait') {
                list[e.id] = new ElementWait(e);
            } else if (e.type == 'ElementCondition') {
                list[e.id] = new ElementCondition(e);
            }
        }

        // Assignment
        for (i = 0; i < json.length; i += 1) {
            e = json[i];
            var o = list[ e.id ];

            if (o.isCondition()) {
                if (e.childYes != null) {
                    o.appendYes(list[e.childYes]);
                }

                if (e.childNo != null) {
                    o.appendNo(list[e.childNo]);
                }

                //o.onaddyes(events.onaddyes);
                //o.onaddno(events.onaddno);
            } else {
                if (e.child != null) {
                    o.append(list[e.child]);
                }

                //o.onadd(events.onadd);
            }

            //o.onclick(events.onclick);
            //o.onhover(events.onhover);
        }
        trigger.organize();
        return trigger;
    }

    constructor(params = {}) {
        this.DefaultRound = 5;
        this.DefaultRectWith = 230;
        this.DefaultRectHeight = 32;
        this.DefaultFill = '#aaa';
        this.DefaultPointSize = 4;
        this.DefaultVerticalSpace = 40;
        this.DefaultActionCircleSize = 16;
        this.MinShift = 0.5;

        this.title = params.title;
        this.setId(params.id);

        this.parent = null;
        this.selected = false;

        this.events = {};

        if (params.options == null) {
            params.options = {};
        }
        this.options = params.options;
    }

    getType() {
        return this.constructor.name;
    }

    getTitle() {
        return this.title;
    }

    setOptions(hash) {
        this.options = hash;
    }

    getOptions() {
        return this.options;
    }

    setTitle(title) {
        this.title = title;
        document.getElementById(this.getId() + '_title').innerHTML = this.title;
    }

    copyEventsFromTrigger() {
        for (var i = 0; i < Object.keys(this.getTrigger().events).length ; i += 1) {
            var event = Object.keys(this.getTrigger().events)[i];
            var func = this.getTrigger().events[event];
            try {
                this[event](func);
                console.log('event added: ' + event);
            } catch (err) {
                console.log('warning: cannot assign event "' + event + '" to element "' + this.title + '"');
            }

        }
    }

    // Custom Events
    onclick(callback) {
        // cover the A (link) element only
        var me = this;
        var selector = '#' + this.getId() + '_textlink';

        $(document).off('click', selector);
        $(document).on('click', selector, function() {
            callback(me);
        });
    }

    onhover(callback) {
        // cover the outbound rect
        var me = this;
        var selector = '#' + this.getId();

        $(document).off('mouseover', selector);
        $(document).on('mouseover', selector, function() {
            callback(me);
        });
    }

    select() {
        this.getTrigger().browse(function(element){
            element.deselect();
        });

        var rect = document.getElementById(this.getId());
        var currentCls = rect.getAttribute('class');
        rect.setAttribute('class', currentCls + ' selected');
        this.selected = true;
    }

    deselect() {
        this.selected = false;
        var rect = document.getElementById(this.getId());
        var clsArray = rect.getAttribute('class').trim().split(/\s+/);

        if (clsArray.indexOf('selected') >= 0) {
            clsArray.splice(clsArray.indexOf('selected') , 1);
        }

        rect.setAttribute('class', clsArray.join(" "));
    }

    /*
    run(speed) {
        this.speed += speed;
        alert(`${this.name} runs with speed ${this.speed}.`);
    }

    stop() {
      this.speed = 0;
      alert(`${this.name} stands still.`);
    }
    */

    drawPlus(id, cx, cy, ctype, container) {
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        var a = document.createElementNS("http://www.w3.org/2000/svg", 'a');
        var text = document.createElementNS("http://www.w3.org/2000/svg", 'text');

        circle.setAttribute('id', id);
        circle.setAttribute('cx', cx);
        circle.setAttribute('cy', cy + this.DefaultActionCircleSize/2);
        circle.setAttribute('ctype', ctype);
        circle.setAttribute('r', this.DefaultActionCircleSize);
        circle.setAttribute('style', 'fill:#fff;stroke:rgb(101, 117, 138);stroke-width:1');

        a.setAttribute('id', id + '-iconlink');
        a.setAttribute('xlink:href', '#');
        a.setAttribute('style',  'cursor:pointer');

        text.setAttribute('x', cx - 5);
        text.setAttribute('y', parseInt(circle.getAttribute('cy')) + 7);
        text.setAttribute('font-size', '16px');

        if (ctype == 'plus') {
            text.innerHTML = '+';
            text.setAttribute('x', cx - 8);
            text.setAttribute('y', parseInt(circle.getAttribute('cy')) + 10);
            text.setAttribute('font-size', '28px');
        } else if (ctype == 'yes') {
            text.innerHTML = 'Y';
        } else {
            text.innerHTML = 'N';
        }

        text.setAttribute('font-family', 'Arial');
        text.setAttribute('fill', '#aaa');

        container.appendChild(g);
        g.appendChild(circle);
        g.appendChild(a);
        a.appendChild(text);
    }

    drawConnector(highId, lowId, container) {
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        g.setAttribute('id', lowId + '_connector');
        container.appendChild(g);

        var high = document.getElementById(highId);
        var low = document.getElementById(lowId);
        var point1;
        var point2;

        if (high.tagName == 'rect') {
            point1 = this.drawCircle('circle1', parseInt(high.getAttribute('x')) + this.DefaultRectWith/2, parseInt(high.getAttribute('y')) + this.DefaultRectHeight, g, false);
        } else {
            point1 = this.drawCircle('circle1', parseInt(high.getAttribute('cx')), parseInt(high.getAttribute('cy')) + this.DefaultActionCircleSize, g, true);
        }

        if (low.tagName == 'rect') {
            point2 = this.drawCircle('circle2', parseInt(low.getAttribute('x')) + this.DefaultRectWith/2, parseInt(low.getAttribute('y')), g, false);
        } else {
            point2 = this.drawCircle('circle2', parseInt(low.getAttribute('cx')), parseInt(low.getAttribute('cy')) - this.DefaultActionCircleSize, g, true);
        }

        this.drawPath(point1, point2, g);
    }

    drawAction(left, top, container, type = 'action') {
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        g.setAttribute('id', this.id + '_group');
        container.appendChild(g);

        var rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");   // Create a <button> element
        rect.setAttribute('id', this.id);
        rect.setAttribute('x', left);
        rect.setAttribute('y', top);
        rect.setAttribute('rx', this.DefaultRound);
        rect.setAttribute('ry', this.DefaultRound);
        rect.setAttribute('width', this.DefaultRectWith);
        rect.setAttribute('height', this.DefaultRectHeight);

        var text1 = document.createElementNS("http://www.w3.org/2000/svg", 'text');

        if (type == 'trigger') {
            rect.setAttribute('class', 'element trigger');
            text1.innerHTML = '&#xf144;';
        } else if (type == 'wait') {
            rect.setAttribute('class', 'element wait');
            text1.innerHTML = '&#xf1da;';
        } else {
            rect.setAttribute('class', 'element action');
            text1.innerHTML = '&#xf0e0;';
        }

        text1.setAttribute('font-family', 'FontAwesome');
        text1.setAttribute('x', left + 10);
        text1.setAttribute('fill', '#fff');
        text1.setAttribute('y', top + this.DefaultRectHeight/2 + 5);
        text1.setAttribute('font-size', '14px');

        var a = document.createElementNS("http://www.w3.org/2000/svg", 'a');
        a.setAttribute('id', this.id + '_textlink');
        a.setAttribute('xlink:href', 'https://stackoverflow.com/questions/34968082/how-to-add-a-link-inside-an-svg-circle');
        a.setAttribute('style',  'cursor:pointer');

        var text2 = document.createElementNS("http://www.w3.org/2000/svg", 'text');
        text2.setAttribute('id', this.getId() + '_title');
        text2.innerHTML = this.title;
        text2.setAttribute('font-family', 'Arial');
        text2.setAttribute('x', left + 30);
        text2.setAttribute('fill', '#fff');
        text2.setAttribute('y', top + this.DefaultRectHeight/2 + 5);
        text2.setAttribute('font-size', '14px');

        g.appendChild(rect);
        g.appendChild(text1);
        g.appendChild(a);
        a.appendChild(text2);

        var plusId = this.id + "-plus";
        this.drawPlus(plusId, parseInt(rect.getAttribute('x')) + this.DefaultRectWith/2, parseInt(rect.getAttribute('y')) + this.DefaultRectHeight + this.DefaultVerticalSpace, 'plus', g);
        this.drawConnector(this.id, plusId, g);
    }

    drawCircle(id, cx, cy, container, zeroPoint = false) {
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        container.appendChild(g);

        var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        circle.setAttribute('id', id);
        circle.setAttribute('cx', cx);
        circle.setAttribute('cy', cy);
        if (zeroPoint) {
            circle.setAttribute('r', 0);
        } else {
            circle.setAttribute('r', this.DefaultPointSize);
        }

        circle.setAttribute('fill', this.DefaultFill);
        g.appendChild(circle);
        return circle;
    }

    drawPath(point1, point2, container) {
        var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
        var p1 = point2.getAttribute('cx') + "," + (parseInt(point2.getAttribute('cy')) - parseInt(point2.getAttribute('r')));
        var p2 = point2.getAttribute('cx')  + "," + (parseInt(point1.getAttribute('cy')) + parseInt(point1.getAttribute('r')));
        var p3 = point1.getAttribute('cx')  + "," + (parseInt(point2.getAttribute('cy')) - parseInt(point2.getAttribute('r')));
        var p4 = point1.getAttribute('cx')  + "," + (parseInt(point1.getAttribute('cy')) + parseInt(point1.getAttribute('r')));

        path.setAttribute('d', "M" + p1  + " C" + p2 + " " + p3  + " " + p4 );
        path.setAttribute('stroke-width', 1);
        path.setAttribute('fill', 'transparent');
        path.setAttribute('stroke', '#ccc');
        container.appendChild(path);
        return path;
    }

    setId(id) {
        if (id == null) {
            var randomId = Math.floor(Math.random() * 999999999) + 100000000;
            this.id = randomId;
        } else {
            this.id = id;
        }
    }

    getId() {
        return this.id;
    }

    clear() {
        var container = document.getElementById(this.id + '_group');
        this.parent = null;

        if (container != null) {
            container.parentNode.removeChild(container);
        }
    }

    getContainer() {
        return document.getElementById(this.getId() + '_group');
    }

    getConnector() {
        return document.getElementById( this.id + '_connector' );
    }

    removeConnector() {
        this.getConnector().parentNode.removeChild(this.getConnector());
    }

    insert(element) {
        // ngắt kết nối cha-con
        var orphan = this.child;

        // Insert element mới
        this.append(element);

        // element mới trở thành cha mới
        if (orphan != null) {
            element.append(orphan);
        }
    }

    isCondition() {
        return false;
    }

    findNoParent() {
        if (this.parent == null) {
            return null;
        }

        if (!this.parent.isCondition() || this.parent.childNo == null) {
            return this.parent.findNoParent();
        } else

        if (this.parent.childNo.getId() != this.getId()) {
            return this.parent.findNoParent();
        }

        return this.parent;
    }

    setPosition(x, y) {
        this.x = x;
        this.y = y;
    }

    findLeftMostSibling(levelAdjust = 0) {
        var minX = this.x;
        var leftMostSibling = null;
        var me = this;
        this.getTrigger().browse(function(element) {
            if ((me.y == element.y || me.y == element.y + levelAdjust) && me.getId() != element.getId()) {
                if (element.x <= minX) {
                    minX = element.x;
                    leftMostSibling = element;
                }
            }
        });

        return leftMostSibling;
    }

    findRightMostSibling(levelAdjust = 0) {
        var minX = null;
        var rightMostSibling = null;
        var me = this;
        this.getTrigger().browse(function(element) {
            if ((me.y == element.y + levelAdjust) && me.getId() != element.getId()) {
                if (element.x >= me.x) {
                    if (minX == null) {
                        minX = element.x;
                        rightMostSibling = element;
                    } else if (minX >= element.x) {
                        minX = element.x;
                        rightMostSibling = element;
                    }
                }
            }
        });

        return rightMostSibling;
    }

    getTrigger() {
        if (this.parent == null) {
            return this;
        }

        return this.parent.getTrigger();
    }

    hasChildren() {
        return (this.child == null) ? false : true;
    }

    getDistance(element) {
        return Math.abs(this.x - element.x);
    }

    reposition() {
        var right = this.findRightMostSibling(-0.5); // sâu hơn nửa line
        while (right != null && this.getDistance(right) < 1.5) {
            right.shiftToRight();
            right = this.findRightMostSibling(-0.5);
        }

        var right = this.findRightMostSibling(); // sâu hơn nửa line
        while (right != null && this.getDistance(right) < 1.5) {
            right.shiftToRight();
            right = this.findRightMostSibling();
        }
    }

    remove() {
        if (this.parent.isCondition()) {
            if (this.parent.childYes.getId() == this.getId()) {
                this.parent.childYes = null;
            } else {
                this.parent.childNo = null;
            }
        } else {
            this.parent.child = null;
        }

        this.clear();
    }
}

class ElementAction extends AutomationElement {

    constructor(params = {}) {
        super(params);

        this.child = null;
    }

    getPlusId() {
        return this.id + '-plus';
    }

    draw(top, left, container = null) {
        this.drawAction(top, left, container);
    }

    append(element) {
        // break the relationship with the child
        if (this.child != null) {
            this.child.clear();
        }

        // clear the canvas only, its relationship is retained
        element.clear();

        var plus = document.getElementById(this.getPlusId());

        element.draw(parseInt(plus.getAttribute('cx')) - this.DefaultRectWith/2, parseInt(plus.getAttribute('cy')) + this.DefaultActionCircleSize/2 + this.DefaultVerticalSpace, this.getContainer());
        this.drawConnector(this.getPlusId(), element.id, element.getContainer());

        this.child = element;
        element.parent = this;

        // IMPORTANT: setPosition must go first, before reappending childrend
        element.setPosition(this.x, this.y + 1);

        // reappend children
        element.reappendChild();

        // copy events from trigger buckets
        element.copyEventsFromTrigger();
    }

    reappendChild() {
        if (this.child != null) {
            var c = this.child;
            c.clear();
            this.append(c);
        }
    }

    shiftToRight() {
        this.parent.shiftToRight();
    }

    onadd(callback) {
        var selector = '#' + this.getPlusId() + '-iconlink';
        var me = this;
        $(document).off('click', selector);
        $(document).on('click', selector, function() {
            callback(me);
        });
    }
}

class ElementTrigger extends ElementAction {
    draw(top, left, container = null) {
        this.drawAction(top, left, container, 'trigger');
    }

    browse(callback) {
        this.traverse(this, callback);
    }

    get(id) {
        var item = null;
        this.browse(function(e) {
            if (e.getId() == id) {
                item = e;
            }
        });

        return item;
    }

    undo() {
        return true;
    }

    redo() {
        return true;
    }

    traverse(element, callback) {
        callback(element);

        if (element.isCondition()) {
            if (element.childYes != null) {
                this.traverse(element.childYes, callback);
            }

            if (element.childNo != null) {
                this.traverse(element.childNo, callback);
            }
        } else {
            if (element.child != null) {
                this.traverse(element.child, callback);
            }
        }
    }

    getLeaves(callback) {
        this.traverse(this, function(element) {
            if (!element.hasChildren()) {
                callback(element);
            }
        });
    }

    organize() {
        this.getLeaves(function(element) {
            element.reposition();
        });
    }

    toJson() {
        var json = [];
        this.browse(function(element){
            var item = { title: element.title, id: element.id, type: element.constructor.name, options: element.options };

            if (element.isCondition()) {
                if (element.childYes != null) {
                    item.childYes = element.childYes.id;
                }

                if (element.childNo != null) {
                    item.childNo = element.childNo.id;
                }
            } else {
                if (element.child != null) {
                    item.child = element.child.id;
                }
            }

            json.push(item);
        });

        return json;
    }

    getSelected() {
        var selected = null;
        this.browse(function(e) {
            if (e.selected) {
                selected = e;
            }
        });

        return selected;
    }
}

class ElementWait extends ElementAction {
    draw(top, left, container = null) {
        this.drawAction(top, left, container, 'wait');
    }
}

class ElementCondition extends AutomationElement {
    constructor(...args) {
        super(...args);

        this.shrink = false;
        this.shift = 0;
        this.expand = 0;
    }

    isCondition() {
        return true;
    }

    drawCondition(left, top, container) {
        // A little bit confusing here
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        g.setAttribute('id', this.id + '_group');
        container.appendChild(g);

        var shifted = this.shift * this.DefaultRectWith;

        var rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");   // Create a <button> element
        rect.setAttribute('id', this.id);
        rect.setAttribute('x', left + shifted);
        rect.setAttribute('y', top);
        rect.setAttribute('rx', this.DefaultRound);
        rect.setAttribute('ry', this.DefaultRound);
        rect.setAttribute('width', this.DefaultRectWith);
        rect.setAttribute('height', this.DefaultRectHeight);
        rect.setAttribute('class', 'element condition');

        var text1 = document.createElementNS("http://www.w3.org/2000/svg", 'text');
        text1.innerHTML = '&#xf059;';
        text1.setAttribute('font-family', 'FontAwesome');
        text1.setAttribute('x', left + shifted + 10);
        text1.setAttribute('fill', '#fff');
        text1.setAttribute('y', top + this.DefaultRectHeight/2 + 5);
        text1.setAttribute('font-size', '14px');

        var a = document.createElementNS("http://www.w3.org/2000/svg", 'a');
        a.setAttribute('id', this.getId() + '_textlink');
        a.setAttribute('xlink:href', 'https://stackoverflow.com/questions/34968082/how-to-add-a-link-inside-an-svg-circle');
        a.setAttribute('style',  'cursor:pointer');

        var text2 = document.createElementNS("http://www.w3.org/2000/svg", 'text');
        text2.innerHTML = this.title;
        text2.setAttribute('id', this.getId() + '_title');
        text2.setAttribute('font-family', 'Arial');
        text2.setAttribute('x', left + shifted + 30);
        text2.setAttribute('fill', '#fff');
        text2.setAttribute('y', top + this.DefaultRectHeight/2 + 5);
        text2.setAttribute('font-size', '14px');

        g.appendChild(rect);
        g.appendChild(text1);
        g.appendChild(a);
        a.appendChild(text2);

        this.drawConditionPlus(rect.getAttribute('x'), rect.getAttribute('y'));
    }

    drawConditionPlus(rectX, rectY) {
        var adjust = 0;
        if (this.shrink == true) {
            adjust += 1 * this.DefaultRectWith / 2;
        }

        var plusYesLabel = this.id + "-plusYesLabel";
        this.drawPlus(
            plusYesLabel,
            parseInt(rectX) - this.DefaultRectWith/2 + adjust,
            parseInt(rectY) + this.DefaultRectHeight + 1.5*this.DefaultVerticalSpace,
            'yes',
            this.getContainer()
        );

        var plusYes = this.id + "-plusYes";
        this.drawPlus(
            plusYes,
            parseInt(rectX) - this.DefaultRectWith/2 + adjust,
            parseInt(rectY) + this.DefaultRectHeight + 2*this.DefaultVerticalSpace + this.DefaultRectHeight - this.DefaultActionCircleSize/2,
            'plus',
            this.getContainer()
        );
        this.drawConnector(plusYesLabel, plusYes, this.getContainer());
        this.drawConnector(this.id, plusYesLabel, this.getContainer());

        var expanded = this.expand * this.DefaultRectWith;

        var plusNoLabel = this.id + "-plusNoLabel";
        this.drawPlus(
            plusNoLabel,
            parseInt(rectX) + this.DefaultRectWith*1.5 + expanded,
            parseInt(rectY) + this.DefaultRectHeight + 1.5*this.DefaultVerticalSpace,
            'no',
            this.getContainer()
        );

        var plusNo = this.id + "-plusNo";
        this.drawPlus(
            plusNo,
            parseInt(rectX) + this.DefaultRectWith*1.5 + expanded,
            parseInt(rectY) + this.DefaultRectHeight + 2*this.DefaultVerticalSpace + this.DefaultRectHeight - this.DefaultActionCircleSize/2,
            'plus',
            this.getContainer()
        );

        this.drawConnector(plusNoLabel, plusNo,  this.getContainer());
        this.drawConnector(this.id, plusNoLabel,  this.getContainer());
    }

    getPlusYesId() {
        return this.id + '-plusYes';
    }

    getPlusNoId() {
        return this.id + '-plusNo';
    }

    draw(top, left, container) {
        this.drawCondition(top, left, container);
    }

    appendYes(element) {
        if (this.childYes != null) {
            this.childYes.clear();
        }

        // clear the canvas only, its relationship is retained
        element.clear();

        var plus = document.getElementById(this.getPlusYesId());

        element.draw(parseInt(plus.getAttribute('cx')) - this.DefaultRectWith/2, parseInt(plus.getAttribute('cy')) + this.DefaultActionCircleSize/2 + this.DefaultVerticalSpace, this.getContainer());
        this.drawConnector(plus.id, element.id, element.getContainer());

        this.childYes = element;
        element.parent = this;

        // IMPORTANT: setPosition must go first before reappending
        element.setPosition(this.x - ((this.shrink) ? 0.5 : 1), this.y + 1.5);
        element.reappendChild();

        element.copyEventsFromTrigger();
    }

    appendNo(element) {
        if (this.childNo != null) {
            this.childNo.clear();
        }

        // clear the canvas only, its relationship is retained
        element.clear();

        var plus = document.getElementById(this.getPlusNoId());
        element.draw(parseInt(plus.getAttribute('cx')) - this.DefaultRectWith/2, parseInt(plus.getAttribute('cy')) + this.DefaultActionCircleSize/2 + this.DefaultVerticalSpace, this.getContainer());
        this.drawConnector(plus.id, element.id, element.getContainer());

        this.childNo = element;
        element.parent = this;

        // IMPORTANT: setPosition must go first before reappending
        element.setPosition(this.x + 1 + this.expand, this.y + 1.5);
        element.reappendChild();

        element.copyEventsFromTrigger();
    }

    reappendChild() {
        this.reappendChildren();
    }

    // Notice: reappend to take advantage of the drawing code in the function
    //         it is better to separate association and drawing code
    reappendChildren() {
        if (this.childYes != null) {
            var y = this.childYes;
            y.clear();
            this.appendYes(y);
        }

        if (this.childNo != null) {
            var n = this.childNo;
            n.clear();
            this.appendNo(n);
        }
    }

    shiftToRight() {
        // if not yet shrink, then just shrink
        if (this.shrink == false) {
            this.shrink = true;
            this.parent.reappendChild();
            return true;
        }

        // otherwise, find NoParent
        var noParent = this.findNoParent();

        if (noParent != null) {
            noParent.expand = noParent.expand + this.MinShift;
            noParent.parent.reappendChild();
        }
    }

    insertYes(element) {
        // ngắt kết nối cha-con
        var orphan = this.childYes;

        // Insert element mới
        this.appendYes(element);

        // element mới trở thành cha mới
        if (orphan != null) {
            element.append(orphan);
        }
    }

    insertNo(element) {
        // ngắt kết nối cha-con
        var orphan = this.childNo;

        // Insert element mới
        this.appendNo(element);

        // element mới trở thành cha mới
        if (orphan != null) {
            element.append(orphan);
        }
    }

    hasChildren() {
        return (this.childYes == null && this.childNo == null) ? false : true;
    }

    reposition() {
        var right = this.findRightMostSibling(-0.5); // sâu hơn nửa line
        while (right != null && this.getDistance(right) < 2) {
            right.shiftToRight();
            right = this.findRightMostSibling(-0.5);
        }

        var right = this.findRightMostSibling(); // sâu hơn nửa line
        while (right != null && this.getDistance(right) < 1.5) {
            right.shiftToRight();
            right = this.findRightMostSibling();
        }
    }

    onaddyes(callback) {
        var selector = '#' + this.getPlusYesId() + '-iconlink';
        var me = this;
        $(document).off('click', selector);
        $(document).on('click', selector, function() {
            callback(me);
        });
    }

    onaddno(callback) {
        var selector = '#' + this.getPlusNoId() + '-iconlink';
        var me = this;
        $(document).off('click', selector);
        $(document).on('click', selector, function() {
            callback(me);
        });
    }
}
