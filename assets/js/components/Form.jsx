import React from 'react'

const className = (...arr) => arr.filter(Boolean).join(' ')

export const Field = React.forwardRef(({name, help, error, children, onChange, required, minLength}, ref) => {
    if(error) {
        help = error
    }
    return <div className={className('form-group', error && 'has-danger')}>
        <label htmlFor={name} className="is-hide">{children}</label>
        <textarea
            ref={ref}
            name={name}
            id={name}
            className={className('form-control', error && 'is-invalid')}
            placeholder={children}
            onChange={onChange}
            required={required}
            minLength={minLength}
        />
        {help && <small className={className('form-text', !error && 'text-muted', error && 'invalid-feedback')}>{help}</small>}
    </div>
})