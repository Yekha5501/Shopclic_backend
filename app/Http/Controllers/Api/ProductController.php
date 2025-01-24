<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // Import the base Controller
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\MessageBroadcasted;

class ProductController extends Controller
{
    // Fetch all products


    public function index()
    {
        // Get products for the authenticated user
        return Product::where('user_id', Auth::id())->get();
    }



    public function broadcastMessage(Request $request)
    {
        // Validate the incoming message request
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $message = $request->message;

        try {
            // Attempt to broadcast the event
            broadcast(new MessageBroadcasted($message))->toOthers();

            // Return success response if broadcasting is successful
            return response()->json([
                'success' => true,
                'message' => 'Message broadcasted successfully!',
            ]);
        } catch (\Exception $e) {
            // Log the full exception with message and stack trace
            Log::error('Pusher error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Optionally, log the entire exception for more details
            Log::error('Full Exception: ' . $e);

            // Return error response
            return response()->json([
                'success' => false,
                'error' => 'Failed to broadcast message. Check logs for more details.',
            ], 500);
        }
    }
}

// then((res) => {
//       axios({
//         method: 'get',
//         url: 'https://YOUR_DOMAIN/api/all',
//       }).then((response) => {
//         let apidata = response.data;
//         console.log(apidata);
//         if(apidata != 0){
//           setUsers(apidata);
//           setLoading(false);
//         }else{
//           setLoading(false);
//         }
//       });
//     });
//   }
