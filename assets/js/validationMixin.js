import {flatten} from 'flat';
import {cloneDeep, fromPairs, get, isObjectLike, toPairs} from 'lodash';

export default {
    computed: {
        validations() {
            return this.evor(this.$v.payload);
        },
    },
    methods: {
        evor(n) {
            return fromPairs(toPairs(n).map(([k, v]) => {
                if (!isObjectLike(v) || ['$iter', '$params', '$model'].some(ik => ik === k)) return [k, v];
                return [
                    k,
                    {
                        ...this.evor(v),
                        ...this.evn(v),
                    },
                ];
            }));
        },
        evn(n) {
            return {
                $required: n.hasOwnProperty('required'),
                $errorMessage: this.bem(n),
                $success: !n.$error && !n.pending && n.$dirty,
            };
        },
        bem(n) {
            if (!n.$error) return null;
            const e = toPairs(n)
                .filter(this.gfr)
                .map(([r]) => window.validationErrorTranslations[r]);
            return e.length === 0 ? null : e[0];
        },
        gfr([k, v]) {
            return k.charAt(0) !== '$' && v === false;
        },
        tui() {
            this.$watch(
                () => cloneDeep(this.payload),
                (n, o) => {
                    Object.entries(flatten(n))
                        .reduce(this.dcfa(flatten(o)), [],)
                        .forEach((c) => {
                            const v = this.guvn(c);
                            if (v) v.$touch();
                        });
                },
                {deep: true},
            );
        },
        dcfa(o) {
            return (c, [f, v]) => {
                return o[f] !== v ? c.concat(f) : c;
            };
        },
        npp(fs, f) {
            return Number.isNaN(parseInt(f, 10)) ? fs.concat([f]) : fs.concat(['$each', f]);
        },
        guvn(c) {
            return get(
                this.$v.payload,
                c.split('.').reduce(
                    this.npp,
                    [],
                ),
                null,
            );
        },
        stfie(w = this.$el) {
            this.$v.$touch();
            this.$nextTick(() => {
                const f = this.gie(w).reduce(this.gfiep, null);
                if (f) {
                    window.scroll({
                        top: f - (window.innerHeight / 2),
                        behavior: 'smooth',
                    });
                }
            });
        },
        gie(w) {
            return Array.from(w.querySelectorAll('.is-invalid, .invalid-message, .invalid-feedback'));
        },
        gfiep(t, c) {
            const _ = this.gep(c);
            return t === null || _ < t ? _ : t;
        },
        gep(e) {
            return e.getBoundingClientRect().y + (window.scrollY);
        },
        isValid() {
            return !this.$v || !this.$v.$invalid;
        },
    },
};
