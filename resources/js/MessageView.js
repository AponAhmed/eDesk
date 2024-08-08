const activeClass = 'bg-gray-200';
const activeClassDark = 'dark:bg-gray-700';
import axios from "axios";
import popup from './popup';
import { Dombuilder as el } from "@aponahmed/dombuilder";

export default class MessageView {
    constructor() {
        this.data = null;
        this.items = document.querySelectorAll('.mail-list-item')
        this.main = document.querySelector("#main");
        this.details = document.querySelector("#messageDetails");
        this.eventsSet();
    }

    eventsSet() {
        this.items.forEach(item => {
            let wraper = item.querySelector(".name-wraper");
            if (item.classList.contains('replies-list')) {
                wraper.addEventListener('click', e => {
                    this.ExReplyTrigger(item);
                })
            } else {
                wraper.addEventListener('click', e => {
                    this.detailsTrigger(item);
                })
            }

        });
    }

    removeCurrent() {
        this.items.forEach(item => {
            if (item.classList.contains(activeClass)) {
                item.classList.remove(activeClass);
            }
            if (item.classList.contains(activeClassDark)) {
                item.classList.remove(activeClassDark);
            }
        });
    }

    closeView() {
        this.main.classList.remove('details-open');
        this.removeCurrent();
    }

    scalitan() {
        this.details.innerHTML = `<div class="flex flex-col w-full">
    <div class="p-2 animate-pulse w-full border-b border-solid border-slate-100 dark:border-slate-800">
        <div class="mb-[10px] flex">
            <div class="h-6 bg-gray-200 dark:bg-gray-700 w-7 mr-2 rounded"></div>
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-3/5"></div>
        </div>
        <div class="flex md:justify-between flex-col md:flex-row">
            <div class="flex-col hidden md:flex md:flex-row w-full md:w-1/2">
                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-3/5 md:mb-0 mb-1 mr-1"></div>
                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-2/5 md:mb-0 mb-1"></div>
            </div>

            <div class="flex mr-1 justify-end">
                <div class="h-6 md:h-3 bg-gray-100 dark:bg-gray-800 rounded w-20"></div>
                <div class="h-6 md:h-3 bg-gray-100 dark:bg-gray-800 rounded w-20 ml-1"></div>
                <div class="h-6 md:h-3 bg-gray-100 dark:bg-gray-800 rounded w-20 ml-1"></div>
                <div class="h-6 md:h-3 bg-gray-100 dark:bg-gray-800 rounded w-8 ml-1"></div>
                <div class="h-6 md:h-3 bg-gray-100 dark:bg-gray-800 rounded w-8 ml-1"></div>
                <div class="h-6 md:h-3 bg-gray-100 dark:bg-gray-800 rounded w-5 ml-1"></div>
            </div>
        </div>
    </div>
    <div class="p-4 animate-pulse w-full mt-4">
        <div class="h-4 bg-gray-100 dark:bg-gray-800 rounded w-4/5 mb-2"></div>
        <div class="h-4 bg-gray-100 dark:bg-gray-800 rounded w-5/5 mb-2"></div>
        <div class="h-4 bg-gray-100 dark:bg-gray-800 rounded w-3/5 mb-2"></div>
        <div class="h-4 bg-gray-100 dark:bg-gray-800 rounded w-4/6 mb-2"></div>
    </div>
</div>
`;
    }

    ExReplyTrigger(item) {
        this.scalitanRelease();
        this.removeCurrent();
        item.classList.add(activeClass);
        this.main.classList.add('details-open');
        //return;
        let id = item.getAttribute('data-id');
        this.getReplymonitor(id)
            .then(() => {
                this.details.innerHTML = `<div class="px-1">${this.data}</div>`;

                let releaseClosebtn = this.details.querySelector('.close-view-button');
                releaseClosebtn.addEventListener('click', this.closeView.bind(this));

                let releaseBtn = this.details.querySelector('#releaseBtn');
                releaseBtn.addEventListener('click', (e) => {
                    releaseBtn.innerHTML = "Sending...";
                    this.releaseMessage().then(() => {
                        window.location.reload();
                    });
                });
            });
    }

    async releaseMessage() {
        let id = this.details.querySelector('#reply_id').value;
        let messageDom = this.details.querySelector('#modifiedMessage');
        //console.log(id,messageDom.innerHTML);
        await axios.post("/" + SUBAPP + '/release', { id: id, modifiedMsg: messageDom.innerHTML })
            .then(response => {
                this.data = response.data;
            });
    }

    detailsTrigger(item) {
        this.scalitan();

        this.removeCurrent();
        item.classList.add(activeClass);
        item.classList.add(activeClassDark);
        this.main.classList.add('details-open');
        //return;
        let id = item.getAttribute('data-id');
        this.getdata(id)
            .then(() => {
                this.randerView();
                if (item.classList.contains('unread')) {
                    item.classList.remove('unread');
                }
            });
    }

    exViewDetails(id, module) {
        this.main.classList.add('details-open');
        axios.post("/" + module + '/message', { id: id })
            .then(response => {
                this.data = response.data;
            }).then(() => {
                this.randerView();
            });
    }

    scalitanRelease() {
        this.details.innerHTML = `<div class="flex flex-col w-full">
    <div class="p-2 animate-pulse w-full border-b border-solid border-slate-100 dark:border-slate-800">
        <div class="mb-[10px] flex">
            <div class="h-6 bg-gray-200 dark:bg-gray-700 w-7 mr-2 rounded"></div>
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-3/5"></div>
        </div>
        <div class="flex md:justify-between flex-col md:flex-row">
            <div class="flex-col hidden md:flex md:flex-row w-full md:w-1/2">
                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-3/5 md:mb-0 mb-1 mr-1"></div>
                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-2/5 md:mb-0 mb-1"></div>
            </div>
        </div>
    </div>
    <div class="p-4 animate-pulse w-full mt-4">
        <div class="h-4 bg-gray-100 dark:bg-gray-800 rounded w-4/5 mb-2"></div>
        <div class="h-4 bg-gray-100 dark:bg-gray-800 rounded w-5/5 mb-2"></div>
        <div class="h-4 bg-gray-100 dark:bg-gray-800 rounded w-3/5 mb-2"></div>
        <div class="h-4 bg-gray-100 dark:bg-gray-800 rounded w-4/6 mb-2"></div>
    </div>
</div>`;
    }

    actions() {
        let labels = this.data.labels.split(",");
        let actDom = new el('div').class('actions').class('justify-end').class('md:absolute').class('static');

        if (!labels.includes('trash')) {
            if (!labels.includes('spam')) {
                //Reply
                let replyStr = 'Reply';
                if (labels.includes('sent')) {
                    replyStr = "Reply Again";
                }
                actDom.append(
                    new el('a').attr('title', `Quick ${replyStr}`)
                        .class('flex').attr('data-w', 900).class('btn-action').class('popup').class('ml-1').class('px-2').class('py-1').attr('href', `/${SUBAPP}/message/${this.data.id}/reply`)
                        .html(`<svg class="w-4 mr-1 rotate-y-180" viewBox="0 0 512 512"><path d="M448 256L272 88v96C103.57 184 64 304.77 64 424c48.61-62.24 91.6-96 208-96v96z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/></svg>  ${replyStr}`).element
                );
            }

            actDom.append(
                new el('a').class('flex').class('btn-action').class('popup').class('ml-1').class('px-2').class('py-1').attr('href', `/${SUBAPP}/message/${this.data.id}/redirect`).html('<svg class="w-4 mr-1" viewBox="0 0 512 512"><path d="M448 256L272 88v96C103.57 184 64 304.77 64 424c48.61-62.24 91.6-96 208-96v96z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/></svg> Redirect').element
            );

            if (SUBAPP == "edesk") {
                if (!labels.includes('spam')) {
                    actDom.append(
                        new el('a').attr('title', "Mark As Spam").attr('href', `/${SUBAPP}/message/${this.data.id}/spam`).class('flex').event('click', e => {
                            this.makeRequest(e);
                        }).class('btn-action').class('ml-1').class('px-2').class('py-1')
                            .html('<svg class="w-4 mr-1" viewBox="0 0 512 512"><circle cx="256" cy="256" r="208" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" d="M108.92 108.92l294.16 294.16"/></svg> Spam').element
                    );
                } else {
                    actDom.append(
                        new el('a').attr('title', "Not Spam").attr('href', `/${SUBAPP}/message/${this.data.id}/notspam`).event('click', e => {
                            this.makeRequest(e);
                        }).class('flex').class('btn-action').class('ml-1').class('px-2').class('py-1').html('<svg class="w-4 mr-1" viewBox="0 0 512 512"><path d="M320 146s24.36-12-64-12a160 160 0 10160 160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 58l80 80-80 80"/></svg>Not Spam').element
                    );
                }


                if (!labels.includes('local')) {
                    actDom.append(
                        new el('a').attr('title', "Mark As Local").attr('href', `/${SUBAPP}/message/${this.data.id}/local`).class('flex').event('click', e => {
                            this.makeRequest(e);
                        }).class('btn-action').class('ml-1').class('px-2').class('py-1')
                            .html('<svg class="w-4 mr-0" viewBox="0 0 512 512"><path d="M256 48c-79.5 0-144 61.39-144 137 0 87 96 224.87 131.25 272.49a15.77 15.77 0 0025.5 0C304 409.89 400 272.07 400 185c0-75.61-64.5-137-144-137z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="256" cy="192" r="48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>').element
                    );
                } else {
                    actDom.append(
                        new el('a').attr('title', "Mark As Real").class('bg-cyan-300').attr('href', `/${SUBAPP}/message/${this.data.id}/notlocal`).event('click', e => {
                            this.makeRequest(e);
                        }).class('flex').class('btn-action').class('ml-1').class('px-2').class('py-1').html('<svg class="w-4 mr-0" viewBox="0 0 512 512"><path d="M256 48c-79.5 0-144 61.39-144 137 0 87 96 224.87 131.25 272.49a15.77 15.77 0 0025.5 0C304 409.89 400 272.07 400 185c0-75.61-64.5-137-144-137z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="256" cy="192" r="48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>').element
                    );
                }
            }




            actDom.append(
                new el('span').attr('title', "Trash").class('flex').event('click', e => {
                    this.labelsModify(e, {
                        id: this.data.id,
                        add: ['trash']
                    });
                }).class('btn-action').class('ml-1').class('px-2').class('py-1')
                    .html('<svg class="w-4" viewBox="0 0 512 512"><path d="M112 112l20 320c.95 18.49 14.4 32 32 32h184c17.67 0 30.87-13.51 32-32l20-320" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 112h352"/><path d="M192 112V72h0a23.93 23.93 0 0124-24h80a23.93 23.93 0 0124 24h0v40M256 176v224M184 176l8 224M328 176l-8 224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>').element
            );
        } else {
            //Trash Box
            actDom.append(
                new el('span').attr('title', "Un-Trash").class('flex').event('click', e => {
                    this.labelsModify(e, {
                        id: this.data.id,
                        remove: ['trash']
                    });
                }).class('btn-action').class('ml-1').class('px-2').class('py-1').html('<svg class="w-4"  viewBox="0 0 512 512"><path d="M320 146s24.36-12-64-12a160 160 0 10160 160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 58l80 80-80 80"/></svg>Un-Trash').element
            );
            actDom.append(
                new el('a').attr('title', "Delete Forever").class('bg-red-300').attr('href', `/message/${this.data.id}/delete`).event('click', e => {
                    this.makeRequest(e);
                }).class('flex').class('btn-action').class('ml-1').class('px-2').class('py-1').html('<svg class="w-4 mr-0" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"/></svg> Delete').element
            );
        }


        if (SUBAPP == "edesk") {
            actDom.append(
                new el('a').class('popup').attr('href', `/${SUBAPP}/message/${this.data.id}/info`).class('sender-info').html(`<svg class="w-5" viewBox="0 0 512 512"><path d="M248 64C146.39 64 64 146.39 64 248s82.39 184 184 184 184-82.39 184-184S349.61 64 248 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M220 220h32v116"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M208 340h88"/><path d="M248 130a26 26 0 1026 26 26 26 0 00-26-26z"/></svg>`).element
            );
        }
        return actDom.element;
    }

    async labelsModify(event, data = {}) {
        event.preventDefault();
        let target = event.target;
        target.innerHTML = '<span class="working"></span>';

        await axios.post("/" + SUBAPP + '/modifi-labels', data)
            .then(response => {
                if (!response.data.error) {
                    window.location.reload();
                }
            });
    }

    randerView() {
        this.details.innerHTML = '';
        //Header
        let header = new el('div').class('message-header').class('pr-12')
            .append(new el('div').class('header-subject').class('mb-1')
                .append(
                    new el('div').class('close-view-button')
                        .html(`<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/></svg>`)
                        .event('click', this.closeView.bind(this)
                        ).element
                ).append(new el('h3').html(this.data.subject).element).element
            )
            .append(new el('div').class('from-details')
                .append(new el('div').class('flex').class('justify-between').class('md:flex-row').class('flex-col')
                    .append(new el('div').class('flex').html(
                        `<div class="hidden md:flex flex-col md:flex-row">
                            <div class="flex mb-1 md:mb-0">
                            <strong class="mr-2 text-sm pr-">From :</strong>
                            <span class='from-name text-slate-500 text-sm'>${this.data.name}</span>
                            <span class='from-name text-slate-400 px-1 text-sm'>&lt;${this.data.email}&gt;</span>
                            </div>
                            <span class='from-name text-slate-400 px-0 md:px-1 text-sm'><strong class="inline-block text-sm md:hidden">Time : </strong> ${this.data.created_at}</span>
                        </div>`
                    ).element).append(
                        this.actions()
                    ).element
                ).element
            );
        //Body wrapper
        let messagebody = new el('div').class('message-body').html(this.data.message);
        this.details.appendChild(header.element);
        this.details.appendChild(messagebody.element);
        new popup();
    }

    async makeRequest(event) {
        event.preventDefault();
        let target = event.target;
        // console.log(target);
        // return;
        target.innerHTML = '<span class="working"></span>';
        let route = target.getAttribute('href');
        await axios.get(route)
            .then(response => {
                if (!response.data.error) {
                    window.location.reload();
                }
            });
    }

    async getdata(id) {
        await axios.post("/" + SUBAPP + '/message', { id: id })
            .then(response => {
                this.data = response.data;
            });
    }
    async getReplymonitor(id) {
        await axios.post("/" + SUBAPP + '/replymonitor', { id: id })
            .then(response => {
                this.data = response.data;
            });
    }
}
