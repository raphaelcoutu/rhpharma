import { forwardRef, useEffect, useImperativeHandle, useRef } from 'react';

export default forwardRef(function Select(
    { className = '', ...props },
    ref,
) {
    return (
        <select
            {...props}
            className={
                'rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 ' +
                className
            }
        >
            {props.children}
        </select>
    );
});
