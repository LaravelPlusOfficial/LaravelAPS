class MakeToc {

    constructor(startLevel = 2) {

        // this.maxHeaderNumber = Math.min(6, 6);

        // this.levels = [];

        this.startLevel = startLevel;

        // for (let headerNumber = this.startLevel; headerNumber <= this.maxHeaderNumber; headerNumber += 1) {

            // this.levels.push('h' + headerNumber);

        // }

        // this.currentLevel = this.startLevel - 1;

    }

    for(headers) {
        
        // let headers = content.querySelectorAll(this.levels.join(','));

        return this.makeToc(headers)

    }

    makeToc(headers) {

        let toc = document.createElement('OL');

        toc.classList.add('toc')

        toc.classList.add(`toc-level-${this.startLevel}`)

        let previousUl = MakeToc.resetPreviousUl(this.startLevel);

        for (let i = 0; i <= headers.length; i++) {

            if (i === headers.length) {

                if (previousUl && previousUl.firstChild) {

                    toc.appendChild(previousUl);

                }

                break;
            }

            let header = headers[i]

            let headerLevel = parseInt(header.tagName.charAt(1), 10);

            // Start
            if (headerLevel === this.startLevel) {

                let li = MakeToc.createLi(header)

                toc.appendChild(li)

                previousUl = MakeToc.resetPreviousUl(headerLevel + 1)

            }
            else {

                let li = MakeToc.createLi(header);

                let parent = previousUl.getElementsByClassName(`toc-level-${headerLevel - 1}`);

                let alreadyExist = previousUl.getElementsByClassName(`toc-level-${headerLevel}`);

                if (previousUl.classList.contains(`toc-level-${headerLevel}`)) {

                    previousUl.appendChild(li);

                }

                else if (alreadyExist.length) {

                    alreadyExist[0].appendChild(li)

                }

                else {

                    if (parent.length) {

                        parent[0].appendChild(MakeToc.createUl(headerLevel, li))

                    }

                    else {
                        previousUl.appendChild(MakeToc.createUl(headerLevel, li))
                    }
                }

            }

        }

        if (toc.firstChild) {
            return toc;
        }

        return null

    }

    static resetPreviousUl(level) {

        let previousUl = document.createElement('OL');

        previousUl.classList.add(`toc-sub`)

        previousUl.classList.add(`toc-level-${level}`)

        return previousUl;

    }

    static createUl(level, li = null) {

        let newParent = document.createElement('OL')

        newParent.classList.add(`toc-sub`)

        newParent.classList.add(`toc-level-${level}`)

        if (li) {
            newParent.appendChild(li)
        }

        return newParent;
    }

    static createLi(headerTag) {

        let textNode = document.createTextNode(headerTag.textContent);

        let link = document.createElement('a');

        link.setAttribute('href', `#${headerTag.id}`);

        link.setAttribute('data-scroll', 'smooth-scroll');

        link.appendChild(textNode)

        let li = document.createElement('LI')

        li.appendChild(link);

        return li
    }

}

export default MakeToc;