import type Echo from 'laravel-echo';

let instance: Echo<'pusher'> | null = null;

/**
 * Lazily creates the Pusher connection. Only the admin panel calls this, so
 * public pages never download pusher-js or open a socket.
 */
export async function useEcho(): Promise<Echo<'pusher'>> {
    if (instance) {
        return instance;
    }

    const [{ default: EchoClient }, { default: Pusher }] = await Promise.all([
        import('laravel-echo'),
        import('pusher-js'),
    ]);

    instance = new EchoClient({
        broadcaster: 'pusher',
        client: new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
            channelAuthorization: { endpoint: '/broadcasting/auth', transport: 'ajax' },
        }),
    });

    return instance;
}
